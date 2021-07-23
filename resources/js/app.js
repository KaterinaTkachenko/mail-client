require("./bootstrap");
const axios = require("axios").default;

(function() {
    'use strict';
    window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
       // if (form.checkValidity() === false) {
        if($('#email').val() == ''){
            event.preventDefault();
            event.stopPropagation();
            $(this).children().children('#email').addClass('is-invalid');
        }
        //form.classList.add('was-validated');
        }, false);
    });
    }, false);
})();

//Email Validation
let email = document.querySelector('#email');
if(email){
    email.addEventListener('change', function(){
      email = document.querySelector('#email');
      var re = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z\-]+\.)+))([a-zA-Z]{2,4})(\]?)$/;
      if(re.test(String(email.value).toLowerCase())){
          email.classList.remove('is-invalid');
      }
      else{
          email.classList.add('is-invalid');
          document.querySelector('.invalid-feedback.email').innerHTML = "Не корректный email!";
      }
    });
}

$(document).ready(function(){ 
    $('#modalSendMail').on('shown.bs.modal', function () {
        $('#email').trigger('focus');
      }) 
    $('.js_folder').click(function(){
        $('#sidebar li.active').not($(this)).removeClass('active');
        $(this).parent().addClass('active');
        let folder = $(this).attr('data-folder');
        $('.spinner').css('display', 'flex');
        $('.content').css('display', 'none');
        axios({
            method: "post",
            url: "/changeFolder",
            data: {folder: folder}
        }).then(response => {
            $("input[name='activeFolder']").val(folder);
            $('.spinner').css('display', 'none'); 
            $('.content__header').css('justify-content', 'flex-end')
            $('.deleteItems').css('display', 'none');           
            $('.content').css('display', 'block');
            $('.mailsInFolder').html(response.data);
            setTimeout(function() {$('.alert').alert('close')}, 3000);
        });
    });   
    
    $('.content__main').click(function(e){
        if (e.target.classList.contains("checkit")){
            let checkboxes = document.querySelectorAll('.checkit');
            for(let ch of checkboxes){
                if(ch.checked)
                {
                    $('.content__header').css('justify-content', 'space-between');
                    $('.deleteItems').css('display', 'inline-block');
                    break;
                }
                else{
                    $('.content__header').css('justify-content', 'flex-end')
                    $('.deleteItems').css('display', 'none');
                }
            }
        }
        if (e.target.classList.contains("openMail")){
            $('.spinner').css('display', 'flex');
            $('.content').css('display', 'none');
            
            let activeFolder = $('#sidebar li.active').children().attr('data-folder');
            let msgno = e.target.getAttribute('data-msgno');
            axios({
                method: "post",
                url: "/showMail",
                data: {msgno: msgno, activeFolder: activeFolder}
            }).then(response => {
                $('.spinner').css('display', 'none'); 
                $('.content').css('display', 'block');
                $('.mailsInFolder').html(response.data);            
            });
        }
    });

    $('.deleteItems').click(function(e){
        e.preventDefault();
        let activeFolder = $('#sidebar li.active').children().attr('data-folder');
        let mailsToDelete = document.querySelectorAll('.checkit:checked');
        let idList = [];
        for(let id of mailsToDelete){
            idList.push(id.getAttribute('data-id'));
        }

        $('.spinner').css('display', 'flex');
        $('.content').css('display', 'none');
        axios({
            method: "post",
            url: "/deleteMail",
            data: {idList: idList, activeFolder: activeFolder}
        }).then(response => {
            $('.spinner').css('display', 'none'); 
            $('.content__header').css('justify-content', 'flex-end')
            $('.deleteItems').css('display', 'none');           
            $('.content').css('display', 'block');
            $('.mailsInFolder').html(response.data);
            setTimeout(function() {$('.alert').alert('close')}, 3000);      
        });   
    })
});