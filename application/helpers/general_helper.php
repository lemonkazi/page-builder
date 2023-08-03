<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom version compare
 *
 * @param   string      $a
 * @param   string      $b
 * @return  integer
 */
function my_version_compare($a, $b)
{
    $a = str_replace('.', '', $a);
    $b = str_replace('.', '', $b);

    $len = max(array(strlen($a), strlen($b)));

    $a = (int) str_pad($a, $len, '0', STR_PAD_RIGHT);
    $b = (int) str_pad($b, $len, '0', STR_PAD_RIGHT);

    if ($a === $b)
    {
        return 0;
    }
    elseif ($a > $b)
    {
        return 1;
    }
    else
    {
        return -1;
    }
}

/**
 * Extract zip file chunk by chunk
 *
 * @param   string      $file_name
 * @param   string      $dir_name
 * @param   string      $exclude
 * @return  void
 */
function zipextract_chunked($file_name, $dir_name, $exclude = NULL)
{
    $flag = FALSE;
    $archive = zip_open($file_name);
    while ($entry = zip_read($archive))
    {
        $size = zip_entry_filesize($entry);
        $name = zip_entry_name($entry);

        if ($exclude)
        {
            $flag = strpos($name, $exclude);
        }

        if ($flag === FALSE)
        {
            if (substr($name, -1) == '/')
            {
                if ( ! is_dir($dir_name . '/' . $name))
                {
                    @mkdir($dir_name . '/' . $name);
                }
            }
            $unzipped = @fopen($dir_name . '/' . $name, 'wb');
            while ($size > 0)
            {
                $chunk_size = ($size > 10240) ? 10240 : $size; // 10KB
                $size -= $chunk_size;
                $chunk = zip_entry_read($entry, $chunk_size);
                if ($chunk !== FALSE)
                {
                    @fwrite($unzipped, $chunk);
                }
            }

            @fclose($unzipped);
        }

        $flag = FALSE;
    }
}

/**
 * Check remote server connection status
 *
 * @param   string      $url
 * @param   integer     $status
 * @return  boolean
 */
function http_response($url, $status = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE); /** remove body */
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //print_r($httpCode); die();
    curl_close($ch);

    if ( ! $head)
    {
        return FALSE;
    }
    if ($status === null)
    {
        if($httpCode < 400)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    else if($status == $httpCode)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

/**
 * Recursively copy files
 * @param  string   $src    Source location
 * @param  string   $dst    Destination location
 * @return void
 */
function recursive_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst, 0755, TRUE);
    while (false !== ($file = readdir($dir)))
    {
        if (($file != '.') && ($file != '..'))
        {
            if (is_dir($src . '/' . $file))
            {
                recursive_copy($src . '/' . $file, $dst . '/' . $file);
            }
            else
            {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * Delete folder recustively
 * @param  string   $src
 * @return void
 */
function recursive_delete($src)
{
    $objects = new RecursiveIteratorIterator (
        new RecursiveDirectoryIterator($src),
        RecursiveIteratorIterator::SELF_FIRST);

    $directories = array(0 => $src);
    $files = array();

    /** Recursive process of Folders. Discovery step for files and directories */
    foreach ($objects as $name => $object)
    {
        if (is_file($name))
        {
            $files[] = $name;
        }
        elseif (is_dir($name))
        {
            $directories[] = $name;
        }
    }

    foreach ($files as $file)
    {
        @unlink($file);
    }

    /** Sort folders in reverse order and delete one at a time */
    arsort($directories);
    foreach ($directories as $directory)
    {
        @chmod($directory, 0777);
        @rmdir($directory);
    }
}

/**
 * Get request protocol, subdomain, domain and host name and tld
 * @param  string   $url
 * @return array
 */
function get_domaininfo($url)
{
    // regex can be replaced with parse_url
    preg_match("/^(https|http|ftp):\/\/(.*?)\//", "$url/" , $matches);
    $parts = explode(".", $matches[2]);
    $tld = array_pop($parts);
    $host = array_pop($parts);
    if ((strlen($tld) <= 3 && strlen($host) <= 3))
    {
        $tld = "$host.$tld";
        $host = array_pop($parts);
    }

    return array(
        'protocol'  => $matches[1],
        'subdomain' => implode(".", $parts),
        'domain'    => trim("$host.$tld", '.'),
        'host'      => $host,
        'tld'       => $tld
    );
}

/**
 * Get the size of directory
 * @param  string   $directory
 * @return float    $size
 */
function get_dir_size($directory)
{
    $size = 0;
    $files= glob($directory.'/*');
    foreach($files as $path)
    {
        is_file($path) && $size += filesize($path);
        is_dir($path) && get_dir_size($path);
    }
    return $size;
}

/**
 * Fix path by adding / at the end
 * @param  string   $path   path to fix
 * @return string           fixed path
 */
function fixpath($path)
{
    $path = str_replace('\\','/',trim($path));
    return (substr($path, -1) != '/') ? $path .= '/' : $path;
}

/**
 * Get the server protocol
 * @return string   https/http
 */
function server_scheme()
{

    // Needed to detect flex SSL
    if (isset($_SERVER['HTTP_CF_VISITOR'])) {
        $cf_visitor = json_decode($_SERVER['HTTP_CF_VISITOR']);
        if (isset($cf_visitor->scheme) && $cf_visitor->scheme == 'https') {
            return 'https';
        }
    }

    if (( ! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || ( ! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443'))
    {
        return 'https';
    }
    else
    {
        return 'http';
    }
}

function mythumb($name)
{
    $ext = explode('.', $name);
    $data = $ext[0] . '_thumb.' . $ext[1];

    return $data;
}

function mymediam($name)
{
    $ext = explode('.', $name);
    $data = $ext[0] . '_mediam.' . $ext[1];

    return $data;
}

function getMonthString($n)
{
    $timestamp = mktime(0, 0, 0, $n, 1, 2005);
    return date("M", $timestamp);
}

function date_to_db($ui_date)
{
    $temp = explode('/', $ui_date);
    $db_date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
    return $db_date;
}

function date_to_ui($db_date)
{
    $temp = explode('-', $db_date);
    $ui_date = $temp[2] . '/' . $temp[1] . '/' . $temp[0];
    return $ui_date;
}

/*
$array - nothing to say
$group_keys - columns which have to be grouped - can be STRING or ARRAY (STRING, STRING[, ...])
$sum_keys - columns which have to be summed - can be STRING or ARRAY (STRING, STRING[, ...])
$count_key - must be STRING - count the grouped keys
*/
function array_distinct($array, $group_keys, $sum_keys = NULL, $count_key = NULL)
{
    if (!is_array($group_keys))
        $group_keys = array($group_keys);
    if (!is_array($sum_keys))
        $sum_keys = array($sum_keys);

    $existing_sub_keys = array();
    $output = array();

    foreach ($array as $key => $sub_array)
    {
        $puffer = NULL;
        #group keys
        foreach ($group_keys as $group_key)
        {
            $puffer .= $sub_array[$group_key];
        }
        $puffer = serialize($puffer);
        if (!in_array($puffer, $existing_sub_keys))
        {
            $existing_sub_keys[$key] = $puffer;
            $output[$key] = $sub_array;
        }
        else
        {
            $puffer = array_search($puffer, $existing_sub_keys);
            #sum keys
            foreach ($sum_keys as $sum_key)
            {
                if (is_string($sum_key))
                {
                    $output[$puffer][$sum_key] += $sub_array[$sum_key];
                }
            }
            #count grouped keys
            if (!array_key_exists($count_key, $output[$puffer]))
            {
                $output[$puffer][$count_key] = 1;
            }
            if (is_string($count_key))
            {
                $output[$puffer][$count_key]++;
            }
        }
    }
    return $output;
}

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0)
    {
        foreach ($array as $k => $v)
        {
            if (is_array($v))
            {
                foreach ($v as $k2 => $v2)
                {
                    if ($k2 == $on)
                    {
                        $sortable_array[$k] = $v2;
                    }
                }
            }
            else
            {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order)
        {
            case SORT_ASC:
            asort($sortable_array);
            break;
            case SORT_DESC:
            arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v)
        {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

/*
$people = array(
12345 => array(
'id' => 12345,
'first_name' => 'Joe',
'surname' => 'Bloggs',
'age' => 23,
'sex' => 'm'
),
12346 => array(
'id' => 12346,
'first_name' => 'Adam',
'surname' => 'Smith',
'age' => 18,
'sex' => 'm'
),
12347 => array(
'id' => 12347,
'first_name' => 'Amy',
'surname' => 'Jones',
'age' => 21,
'sex' => 'f'
)
);

print_r(array_sort($people, 'age', SORT_DESC)); // Sort by oldest first
print_r(array_sort($people, 'surname', SORT_ASC)); // Sort by surname
 */

function country_list()
{
    return '
    <option value="AF">Afghanistan (افغانستان)</option>
    <option value="AX">Aland Islands</option>
    <option value="AL">Albania (Shqipëria)</option>
    <option value="DZ">Algeria (الجزائر)</option>
    <option value="AS">American Samoa</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarctica</option>
    <option value="AG">Antigua and Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenia (Հայաստան)</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria (Österreich)</option>
    <option value="AZ">Azerbaijan (Azərbaycan)</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain (البحرين)</option>
    <option value="BD">Bangladesh (বাংলাদেশ)</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus (Белару́сь)</option>
    <option value="BE">Belgium (België)</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin (Bénin)</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan (འབྲུག་ཡུལ)</option>
    <option value="BO">Bolivia</option>
    <option value="BA">Bosnia and Herzegovina (Bosna i Hercegovina)</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil (Brasil)</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei (Brunei Darussalam)</option>
    <option value="BG">Bulgaria (България)</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi (Uburundi)</option>
    <option value="KH">Cambodia (Kampuchea)</option>
    <option value="CM">Cameroon (Cameroun)</option>
    <option value="CA">Canada</option>
    <option value="CV">Cape Verde (Cabo Verde)</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic (République Centrafricaine)</option>
    <option value="TD">Chad (Tchad)</option>
    <option value="CL">Chile</option>
    <option value="CN">China (中国)</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoros (Comores)</option>
    <option value="CG">Congo</option>
    <option value="CD">Congo, Democratic Republic of the</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="CI">Côte d Ivoire</option>
    <option value="HR">Croatia (Hrvatska)</option>
    <option value="CU">Cuba</option>
    <option value="CY">Cyprus (Κυπρος)</option>
    <option value="CZ">Czech Republic (Česko)</option>
    <option value="DK">Denmark (Danmark)</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt (مصر)</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea (Guinea Ecuatorial)</option>
    <option value="ER">Eritrea (Ertra)</option>
    <option value="EE">Estonia (Eesti)</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland (Suomi)</option>
    <option value="FR">France</option>
    <option value="GF">French Guiana</option>
    <option value="PF">French Polynesia</option>
    <option value="TF">French Southern Territories</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia (საქართველო)</option>
    <option value="DE">Germany (Deutschland)</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece (Ελλάς)</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GG">Guernsey</option>
    <option value="GN">Guinea (Guinée)</option>
    <option value="GW">Guinea-Bissau (Guiné-Bissau)</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti (Haïti)</option>
    <option value="HM">Heard Island and McDonald Islands</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary (Magyarország)</option>
    <option value="IS">Iceland (Ísland)</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran (ایران)</option>
    <option value="IQ">Iraq (العراق)</option>
    <option value="IE">Ireland</option>
    <option value="IM">Isle of Man</option>
    <option value="IL">Israel (ישראל)</option>
    <option value="IT">Italy (Italia)</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan (日本)</option>
    <option value="JE">Jersey</option>
    <option value="JO">Jordan (الاردن)</option>
    <option value="KZ">Kazakhstan (Қазақстан)</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KW">Kuwait (الكويت)</option>
    <option value="KG">Kyrgyzstan (Кыргызстан)</option>
    <option value="LA">Laos (ນລາວ)</option>
    <option value="LV">Latvia (Latvija)</option>
    <option value="LB">Lebanon (لبنان)</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libya (ليبيا)</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania (Lietuva)</option>
    <option value="LU">Luxembourg (Lëtzebuerg)</option>
    <option value="MO">Macao</option>
    <option value="MK">Macedonia (Македонија)</option>
    <option value="MG">Madagascar (Madagasikara)</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives (ގުޖޭއްރާ ޔާއްރިހޫމްޖ)</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania (موريتانيا)</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexico (México)</option>
    <option value="FM">Micronesia</option>
    <option value="MD">Moldova</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia (Монгол Улс)</option>
    <option value="ME">Montenegro (Црна Гора)</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco (المغرب)</option>
    <option value="MZ">Mozambique (Moçambique)</option>
    <option value="MM">Myanmar (Burma)</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru (Naoero)</option>
    <option value="NP">Nepal (नेपाल)</option>
    <option value="NL">Netherlands (Nederland)</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="MP">Northern Mariana Islands</option>
    <option value="KP">North Korea (조선)</option>
    <option value="NO">Norway (Norge)</option>
    <option value="OM">Oman (عمان)</option>
    <option value="PK">Pakistan (پاکستان)</option>
    <option value="PW">Palau (Belau)</option>
    <option value="PS">Palestinian Territories</option>
    <option value="PA">Panama (Panamá)</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru (Perú)</option>
    <option value="PH">Philippines (Pilipinas)</option>
    <option value="PN">Pitcairn</option>
    <option value="PL">Poland (Polska)</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar (قطر)</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania (România)</option>
    <option value="RU">Russia (Россия)</option>
    <option value="RW">Rwanda</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts and Nevis</option>
    <option value="LC">Saint Lucia</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent and the Grenadines</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">São Tomé and Príncipe</option>
    <option value="SA">Saudi Arabia (المملكة العربية السعودية)</option>
    <option value="SN">Senegal (Sénégal)</option>
    <option value="RS">Serbia (Србија)</option>
    <option value="CS">Serbia and Montenegro (Србија и Црна Гора)</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore (Singapura)</option>
    <option value="SK">Slovakia (Slovensko)</option>
    <option value="SI">Slovenia (Slovenija)</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia (Soomaaliya)</option>
    <option value="ZA">South Africa</option>
    <option value="GS">South Georgia and the South Sandwich Islands</option>
    <option value="KR">South Korea (한국)</option>
    <option value="ES">Spain (España)</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan (السودان)</option>
    <option value="SR">Suriname</option>
    <option value="SJ">Svalbard and Jan Mayen</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden (Sverige)</option>
    <option value="CH">Switzerland (Schweiz)</option>
    <option value="SY">Syria (سوريا)</option>
    <option value="TW">Taiwan (台灣)</option>
    <option value="TJ">Tajikistan (Тоҷикистон)</option>
    <option value="TZ">Tanzania</option>
    <option value="TH">Thailand (ราชอาณาจักรไทย)</option>
    <option value="TL">Timor-Leste</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia (تونس)</option>
    <option value="TR">Turkey (Türkiye)</option>
    <option value="TM">Turkmenistan (Türkmenistan)</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine (Україна)</option>
    <option value="AE">United Arab Emirates (الإمارات العربيّة المتّحدة)</option>
    <option value="GB">United Kingdom</option>
    <option value="US">United States</option>
    <option value="UM">United States minor outlying islands</option>
    <option value="UY">Uruguay</option>
    <option value="UZ">Uzbekistan (O zbekiston)</option>
    <option value="VU">Vanuatu</option>
    <option value="VA">Vatican City (Città del Vaticano)</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Vietnam (Việt Nam)</option>
    <option value="VG">Virgin Islands, British</option>
    <option value="VI">Virgin Islands, U.S.</option>
    <option value="WF">Wallis and Futuna</option>
    <option value="EH">Western Sahara (الصحراء الغربية)</option>
    <option value="YE">Yemen (اليمن)</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>';
}