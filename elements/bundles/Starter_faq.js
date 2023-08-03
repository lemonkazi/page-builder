const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
  const question = item.querySelector('.faq-question');
  const answer = item.querySelector('.faq-answer');
  const arrowIcon = question.querySelector('.fas.fa-chevron-down');

  question.addEventListener('click', () => {
    // Toggle the visibility of the answer with a slide effect
    if (answer.style.maxHeight) {
      answer.style.maxHeight = null;
      arrowIcon.classList.remove('fa-chevron-up');
      arrowIcon.classList.add('fa-chevron-down');
    } else {
      answer.style.maxHeight = answer.scrollHeight + 'px';
      arrowIcon.classList.remove('fa-chevron-down');
      arrowIcon.classList.add('fa-chevron-up');
    }
  });
});
