document.addEventListener('DOMContentLoaded', function() {
   let userBox = document.querySelector(".header .header-2 .user-box");
   let navbar = document.querySelector(".header .header-2 .navbar");
   let noButtons = document.querySelectorAll(".no-btn");

   document.querySelector("#user-btn").onclick = () => {
       userBox.classList.toggle("active");
       navbar.classList.remove("active");
   };

   document.querySelector("#menu-btn").onclick = () => {
       navbar.classList.toggle("active");
       userBox.classList.remove("active");
   };

   window.onscroll = () => {
       userBox.classList.remove("active");
       navbar.classList.remove("active");

       if (window.scrollY > 0) {
           document.querySelector(".header .header-2").classList.add("active");
       } else {
           document.querySelector(".header .header-2").classList.remove("active");
       }
   };

   noButtons.forEach(function(noBtn) {
       noBtn.addEventListener("click", function(e) {
           e.preventDefault();
           let replyDiv = this.closest('.box').querySelector(".user-reply");
           replyDiv.classList.remove("notActive");
           replyDiv.classList.add("active");
       });
   });
});