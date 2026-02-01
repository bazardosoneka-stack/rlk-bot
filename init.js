document.addEventListener('DOMContentLoaded', function () {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try Chromium.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
});

function notifyMe(cartao) {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification('+1 PAGAMENTO APROVADO', {
      icon: 'http://ap.imagensbrasil.org/images/2017/06/30/Mercado-Livre-Icon.png',
      body: "R$5.00 ~ "+cartao,
    });

    notification.onclick = function () {
         notification.close();
    };

  }

}

$(document).ready(function($) {

	 function count(mixed_var, mode) {
          var key, cnt = 0;
          if (mixed_var === null || typeof mixed_var === 'undefined') {
              return 0;
          } else if (mixed_var.constructor !== Array && mixed_var.constructor !== Object) {
              return 1;
          }
          if (mode === 'COUNT_RECURSIVE') {
              mode = 1;
          }
          if (mode != 1) {
              mode = 0;
          }
          for (key in mixed_var) {
              if (mixed_var.hasOwnProperty(key)) {
                  cnt++;
                  if (mode == 1 && mixed_var[key] && (mixed_var[key].constructor === Array || mixed_var[key].constructor ===
                          Object)) {
                      cnt += this.count(mixed_var[key], 1);
                  }
              }
          }
          return cnt;
      }


	$("#start").click(function(event) {
		var reprovadas = 0;
		var aprovadas = 0;
		var testadas = 0;
		var lista2 = $("#ccs").val().split("\n");
    var lista = lista2.reduce(function(a,b){
    if (a.indexOf(b) < 0 ) a.push(b);
    return a;
    },[]);
		$("#carregadasCount").html(count(lista));
		$.each(lista, function(index, el) {
			$.ajax({
				url: 'api.php',
				type: 'GET',
				dataType: 'html',
				data: {"cartao": el},
				success: function(resposta){
					if(resposta.indexOf('APROVADA') > -1){
						
						$("#aprovadas").append(resposta+"\n");
						aprovadas++;
						$("#aprovadasCount").html(aprovadas);
						notifyMe(el);
						var audio = new Audio('aprovado.mp3');
						audio.play();

					}else if(resposta.indexOf('REPROVADA') > -1){
						$("#reprovadas").append(resposta);
						reprovadas++;
						$("#reprovadasCount").html(reprovadas);
					}else if(resposta.indexOf('ERRO') > -1){
						$("#reprovadas").append(resposta);
						reprovadas++;
						$("#reprovadasCount").html(reprovadas);
					}
					testadas = reprovadas + aprovadas;
					$("#testadasCount").html(testadas);
				}
			});
			
		});
	});
});