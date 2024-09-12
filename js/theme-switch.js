const themeSwitch = document.getElementById('theme-switch');


document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    document.body.classList.toggle('dark-mode', savedTheme === 'dark');
    themeSwitch.checked = savedTheme === 'dark';
  }
});

themeSwitch.addEventListener('change', () => {
  const isDarkMode = themeSwitch.checked;
  document.body.classList.toggle('dark-mode', isDarkMode);
  localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
});
