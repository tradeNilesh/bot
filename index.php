<html>
<head>
	<title>DialogFlow API Example</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://code.responsivevoice.org/responsivevoice.js"></script> 
	
	<script type="text/javascript">
		var accessToken ="ee767bf0d2dc48fcac87cc5a520b0a5a";
		var baseUrl = "https://api.dialogflow.com/v1/";
		$(document).ready(function() {

		var welcomeMsg = "Hi nilesh welcome to TradeSocio";
		setResponse(welcomeMsg);
		responsiveVoice.speak(welcomeMsg);


			$("#botInput").keypress(function(event) {
				if (event.which == 13) {
					event.preventDefault();
					send();
					this.value = '';
				}
			});
			$("#botRec").click(function(event) {
				switchRecognition();
			});
		});
		var recognition;
		function startRecognition() {
			recognition = new webkitSpeechRecognition();
			recognition.onstart = function(event) {
				updateRec();
			};
			recognition.onresult = function(event) {
				var text = "";
				for (var i = event.resultIndex; i < event.results.length; ++i) {
					text += event.results[i][0].transcript;
				}
				setInput(text);
				stopRecognition();
			};
			recognition.onend = function() {
				stopRecognition();
			};
			recognition.lang = "en-US";
			recognition.start();
		}
		function stopRecognition() {
			if (recognition) {
				recognition.stop();
				recognition = null;
			}
			updateRec();
		}
		function switchRecognition() {
			if (recognition) {
				stopRecognition();
			} else {
				startRecognition();
			}
		}
		function setInput(text) {
			$("#botInput").val(text);
			send();
		}
		function updateRec() {
			$("#botRec").text(recognition ? "Stop" : "Talk");
        }
        
        //responsiveVoice.speak("Your balance of Account Number 5775 is 4987.59 USD.", "UK English Male");
		function send() {
            var text = $("#botInput").val();
            //text = text + "&text_temp=demo";
			conversation.push('<span class="botReplyUser"><span class="botIdUser">Me:</span><span class="botMsgUser">' + text + '</span></span>'); //div
			$("#response").html(conversation.join("")); //div	
			$.ajax({
				type: "POST",
				url: baseUrl + "query?v=20170712&data=val",
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				headers: {
					"Authorization": "Bearer " + accessToken
				},
				data: JSON.stringify({ query: text, lang: "en", contexts :[{name:"data","parameters" : {accountID : "32707",profileID:"22319"}}], sessionId: "somerandomthing"}),
				success: function(data) {
					//console.log('-----------Data-----------');
					//console.log(data);
					//console.log('-----------Data end-----------');
					var respText = data.result.fulfillment.speech;
					//console.log("Respuesta: " + respText);
                    setResponse(respText);
                    responsiveVoice.speak(respText);
				},
				error: function() {
					setResponse("Internal Server Error");
				}
			});
        }
		
        
		function setResponse(val) {
			conversation.push('<span class="botReplyBot"><span class="botIdBot">sAmI:</span><span class="botMsgBot">' + val + '</span></span>'); //div
			$("#response").html(conversation.join("")); //div
			$("#response-container").scrollTop($("#response").height());
			$("#response .botReplyUser").removeClass("botReplyUserCurrent");
			$("#response .botReplyUser").last().addClass("botReplyUserCurrent");
			$("#response .botReplyBot").removeClass("botReplyBotCurrent");
			$("#response .botReplyBot").last().addClass("botReplyBotCurrent");
        }
        
		console.log = function() {};
		var conversation = [];
	</script>
	<style type="text/css">
		.bot{width:340px; height:340px; padding:5px; border-radius:5px; border:1px solid #eee; background:#fff; overflow:hidden;}
		.bot #response-container{width:calc(100% + 50px); height:280px; overflow-x:hidden; overflow-y:auto;}
		.bot #response{width:340px;}
		.bot .botReplyUser{display:block; width:calc(100% - 10px); padding:5px; border-radius:5px; background:#eee; color:#888; margin-bottom:5px;}
		.bot .botReplyBot{display:block; width:calc(100% - 12px); padding:5px; border-radius:5px; border:1px solid #eee; background:#fff; color:#888; margin-bottom:5px;}
		.bot .botReplyUserCurrent{color:#555; background:#ddd;}
		.bot .botReplyBotCurrent{color:#555;}
		.bot .botIdUser{display:none; }
		.bot .botIdBot{display:none; }
		.bot .botMsgUser{display:inline-block; }
		.bot .botMsgBot{display:inline-block; }
		.bot #botInput{display:inline-block; width:290px; height:40px; padding:5px; border-radius:5px; border:1px solid #eee; line-height:30px; margin-right:10px;}
		.bot #botRec{display:inline-block; width:40px; height:40px; border-radius:5px; border:1px solid #eee; background:#fff;}
	</style>
</head>
<body>
	<div class="bot">
  		<div id="response-container">
  			<div id="response"></div>
		</div>
 		<br />
    	<input id="botInput" type="text" placeholder="Hi, how can I help?" autocomplete="off" /><button id="botRec">Talk</button>
	</div>
</body>
</html>