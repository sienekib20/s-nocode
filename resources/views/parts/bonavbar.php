<nav class="bonavbar">
    <div class="container">
    	<div class="row">
    		<div class="col-md-2">
    			<button type="button">
    				<span class="line"></span>
    				<span class="line"></span>
    				<span class="line"></span>
    			</button>
    		</div>
    		<div class="col-md-6">
                <span>Olá, <?= 'Apelido' ?> </span>      
                <small class="text-muted d-block">Checa os seus dados</small>
            </div>
    		<div class="col-md-4 d-flex align-items-center justify-content-end">
    			<small class="text-muted" id="current-date"></small>
                <small class="bi bi-bell-fill"></small>
    		</div>
    	</div>
    </div>
</nav> <!--/bonavbar-->

<script>
    // Array para mapear os nomes dos meses
    const meses = [
        "janeiro", "fevereiro", "março", "abril", "maio", "junho",
        "julho", "agosto", "setembro", "outubro", "novembro", "dezembro"
    ];

    // Array para mapear os nomes dos dias da semana
    const diasSemana = [
        "Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira",
        "Quinta-feira", "Sexta-feira", "Sábado"
    ];

    // Função para obter a data formatada
    function formatarData(data) {
        const diaSemana = diasSemana[data.getDay()];
        const dia = data.getDate();
        const mes = meses[data.getMonth()];
        const ano = data.getFullYear();

        return `${diaSemana}, ${dia} de ${mes} ${ano}`;
    }

    // Obtendo a data atual
    const dataAtual = new Date();

    // Formatando a data no formato desejado
    const dataFormatada = formatarData(dataAtual);

    console.log(dataFormatada); // Saída: Segunda-feira 14 fevereiro, 2024 (para a data atual)

    var date = new Date()

    $('#current-date').text(dataFormatada);
</script>