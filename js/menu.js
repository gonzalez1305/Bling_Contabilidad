document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.querySelector('.navbar');
    window.onscroll = function() {
      if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
      } else {
        navbar.classList.remove('navbar-scrolled');
      }
    };
  });