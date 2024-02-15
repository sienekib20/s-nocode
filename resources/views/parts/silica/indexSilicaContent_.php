
<script>
    var news = [];
    news[0] = {title: "Pague pelo que utiliza", description: "Planos e preços a medida das suas necessidades"};
    news[1] = {title: "Pode usar em qualquer dispositivo", description: "Compactível com qualquer dispositivo (computador, tablet ou telemóvel)"};
    news[2] = {title: "Trabalhe no horário que quiser", description: "Disponível 24horas/dia e 7dias/semana"};
    news[3] = {title: "Formulação de preço personalizada", description: "Saiba de onde vêm as receitas, e para onde vão as despesas em tempo real"};
    news[4] = {title: "Multiplas filiais", description: "Pode gerir mais de uma filial, cada uma com suas actividades e armazéns de estocagem"};
    news[5] = {title: "Permissões e acessos personalizados", description: "Saiba qual funcionário é mais productivo"};
    news[6] = {title: "Formação gratuíta", description: "Não terá qualquer custo adicional pela formação."};
    news[7] = {title: "Assistência técnica gratuíta", description: "Disponha dos nossos canais de assistência técnica, sempre que desejar"};


    var newsIdx = 0;

  /*  function changeNew() {
        var currentNew = news[Math.floor(Math.random() * news.length)];
        document.getElementById('lbSilContNewsTitle').innerHTML = currentNew.title;
        document.getElementById('lbSilContNewsDescription').innerHTML = currentNew.description;
    }
    setInterval(changeNew, 6000);*/


</script>


<div class="divSilicaContent">
    <div class="divSilicaContent-Inside">
        <div class="divSilicaLogo">
        </div>
    <!--    <h3 id="lbSilContNewsTitle" style="color: #999">NOVIDADES</h3>
        <h2 id="lbSilContNewsDescription"> Dá para todos, desde pessoas singulares, empresas, organizações não governamentais,
            supermercados, lojas, prestadores de serviços, hoteis, restaurantes, imobilárias,
            mobiliaárias, até mesmo para igrejas</h2>-->

        <div style="margin-top: 50px; font-size: 13px; color: white;">
            <span>Contactos: (+244) <a href="tel:993700757">993 700 757</a> / <a href="tel:948109778">948 109 778</a></span><br>
            <span>E-mail: <a href="mailto:cc@silicaweb.ao">cc@silicaweb.com</a> / <a href="mailto:at@silicaweb.ao">at@silicaweb.ao</a></span><br>
            <span>Website: <a href="https://www.silicaweb.ao" target="_blank">www.silicaweb.ao</a></span><br>
            <span>Facebook: <a href="https://www.facebook.com/silicaweb" target="_blank">/silicaweb</a></span><br>
            <span>WhatsApp: <a href="https://wa.me/244993700757" target="_blank">993 700 757</a></span>
        </div>
    </div>
</div>

<?= parts('silica._notifications') ?>