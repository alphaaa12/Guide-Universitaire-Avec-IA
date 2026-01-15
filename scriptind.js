let index = 0;
const slides = document.getElementById('slides');
const totalImages = slides.children.length;

setInterval(() => {
  index = (index + 1) % totalImages;
  slides.style.transform = `translateX(-${index * 100}%)`;
}, 3000); // يتبدّل كل 3 ثواني