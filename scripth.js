document.addEventListener('DOMContentLoaded', () => {
    const burger = document.getElementById('burger');
    const navLinks = document.getElementById('navLinks');
  
    burger.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  });
  
 // header.transparent {
   // background-color: rgba(94, 44, 165, 0.7); /* شفاف */
   // backdrop-filter: blur(5px); /* يعطي لمعة خفيفة ووضوح */
//  }