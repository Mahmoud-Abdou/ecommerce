<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<h1> {{setting('app_name')}} Support:</h1>

<p>For more support you conatact us at info@a-gh.com</p>

FAQ:
<div id="faq"></div>

<script>
$(document).ready(function(){
    $.ajax({
    url: "/api/faqs",
    success: function(html){
        console.log(html);
        x=``;
        html.data.forEach(element => {
            x+=`
            <h1>${element.question}</h1>
            <p>${element.answer}</p>
            `; 
        });
        $("#faq").html(x);
    }
    });
});
</script>

