 <footer class="footer">
     <div class="container">
         <div class="row align-items-center flex-row-reverse">
             <div class="col-md-12 col-sm-12 text-center">
                 All Rights Reserved@<?= date('Y'); ?>, National Draw Licence No:1020580
                 <!-- Copyright © <span id="year"></span> <a href="javascript:void(0)">National Draw</a>.<a href="javascript:void(0)">  </a> All rights reserved. -->
             </div>
         </div>
     </div>
 </footer>

 <script>
     (function() {
         openSideMenu(localStorage.getItem("parent"), true);
     })();

     function openSideMenu(menuID, screenLoad = false) {
         setCookie('exitScreen', window.location.href, 30);
         if (menuID.search('main') >= 0) {
             let history = localStorage.getItem("parent");
             if (history !== null && history !== undefined && history !== '') {
                 if (history !== menuID) {
                     localStorage.setItem("parent", menuID);
                 }
             } else {
                 localStorage.setItem("parent", menuID);
             }

             if (!$("#" + menuID).parents('li').hasClass('is-expanded') && screenLoad) {
                 $("#" + menuID).parents('li').addClass('is-expanded');
             }
         }

         //  let usersCookie = getCookie('cookie_memid');
         //  if (usersCookie !== '' && usersCookie !== null && usersCookie !== undefined) {

         //  }
     }

     function getCookie(cname) {
         let name = cname + "=";
         let decodedCookie = decodeURIComponent(document.cookie);
         let ca = decodedCookie.split(';');
         for (let i = 0; i < ca.length; i++) {
             let c = ca[i];
             while (c.charAt(0) == ' ') {
                 c = c.substring(1);
             }
             if (c.indexOf(name) == 0) {
                 return c.substring(name.length, c.length);
             }
         }
         return "";
     }

     function setCookie(cname, cvalue, exdays) {
         const d = new Date();
         d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
         let expires = "expires=" + d.toUTCString();
         document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
     }
 </script>