 window.addEventListener("load",function(){
 
    var chatbox = document.createElement("div");
    chatbox.className = "chatbox chatbox--tray";
    document.body.appendChild(chatbox);
    var chatbox__title = document.createElement("div");
    chatbox__title.className = "chatbox__title";
    chatbox.appendChild(chatbox__title);
    var chatbox_h5 = document.createElement("h5");
    chatbox__title.appendChild(chatbox_h5);
    var chatbox_a = document.createElement("a");
    chatbox_a.href = "#";
    chatbox_a.innerText = "Operadores Online";
    chatbox_h5.appendChild(chatbox_a);
    var chatbox__title__tray = document.createElement("button");
    chatbox__title__tray.className = "chatbox__title__tray";
    chatbox__title.appendChild(chatbox__title__tray);
    var span = document.createElement("span");
    chatbox__title__tray.appendChild(span);
    var chatbox__title__close = document.createElement("button");
    chatbox__title__close.className = "chatbox__title__close";
    chatbox__title.appendChild(chatbox__title__close);
    var span = document.createElement("span");
    span.innerHTML = "<svg viewBox='0 0 12 12' width='12px' height='12px'><line stroke='#FFFFFF' x1='11.75' y1='0.25' x2='0.25' y2='11.75'></line><line stroke='#FFFFFF' x1='11.75' y1='11.75' x2='0.25' y2='0.25'></line></svg>";
    chatbox__title__close.appendChild(span);
    var chatbox__body = document.createElement("div");
    chatbox__body.className = "chatbox__body";
    chatbox__body.id = "chatbox_body_content";
    chatbox.appendChild(chatbox__body);
    var chat_context = document.createElement("input");
    chat_context.type = "hidden";
    chat_context.name = "conversation_id";
    chat_context.id = "chat_context";
    chat_context.value = "";
    chatbox.appendChild(chat_context);
    var chatbox__message = document.createElement("input");
    chatbox__message.type = "text";
    chatbox__message.className = "chatbox__message";
    chatbox__message.placeholder = "Escreva aqui";
    chatbox__message.id = "user_input";
    chatbox.appendChild(chatbox__message);
 
 
    var o = document.getElementsByClassName("chatbox")[0],
        e = document.getElementsByClassName("chatbox__title")[0],
        t = document.getElementsByClassName("chatbox__title__close")[0];
    
    function envio(e) {
        this.sendMessage(e);
        var p = document.createElement("p");
        p.className = "userText";
        p.innerText = e;
        chatbox__body.append(p),document.getElementById("user_input").value = "",
        chatbox__body.scrollTop = 1e10;
    }
    if(e !== undefined && t !== undefined){
        e.addEventListener('click', function(){
            o.classList.toggle("chatbox--tray"),o.classList.contains("chatbox--closed") && (o.classList.remove("chatbox--closed"), o.classList.add("chatbox--tray"))
        }, true);
        t.addEventListener('click', function(event){
            event.preventDefault(),o.classList.add("chatbox--tray")
        });
    }
    if(document.getElementById("user_input") !== null){
        document.getElementById("user_input").addEventListener('keypress',function (e) {
            if (13 == (e.keyCode ? e.keyCode : e.which)) {
                var t =  document.getElementById("user_input").value;
                document.getElementById("chat_context").value = "";
                e.preventDefault();
                envio(t)
            }
        });
    }
 });
 const localStorage = window.localStorage;
 let idClient = undefined;
        const socket = io(vars.url_back, {
            query: {
                token: 'Bearer '+(vars.token !== undefined ? vars.token : '')
            }
        });
        function removeCharacteres(e) {
         return e.replace(/[^a-zA-Z0-9]/g, "")
     }
        socket.emit('getMessage', { idClient:localStorage.getItem("idClientChatCriaSystem"+removeCharacteres(document.location.host))});
        socket.on('getMessage', (response) => {
            if(response.length > 0){
                response.forEach(element => {
                 var p = document.createElement("p");
                 if(element.nameOperator !== undefined && element.nameOperator !== null && element.nameOperator !== ""){
                     p.className = "operatorText";
                     p.innerText = element.nameOperator + ": " + element.message;
                 }else{
                     p.className = "userText";
                     p.innerText = element.message;
                 }
                 document.getElementById("chatbox_body_content").append(p); 
                 document.getElementById("chatbox_body_content").scrollTop = 1e10;
             })
            }
        });
        function setIdClient(id){
             if(localStorage.getItem("idClientChatCriaSystem"+removeCharacteres(document.location.host)) === undefined || localStorage.getItem("idClientChatCriaSystem"+removeCharacteres(document.location.host)) === null){
                 idClient = id;
                 localStorage.setItem("idClientChatCriaSystem"+removeCharacteres(document.location.host),id);
             }
         }
        function received(text, nameOperator){
            var p = document.createElement("p")
            p.className = "operatorText";
            p.innerText = nameOperator + ": " + text;
            document.getElementById("chatbox_body_content").append(p); 
            document.getElementById("chatbox_body_content").scrollTop = 1e10;
        }
        socket.on('private message', (response) => {
             if(response.idClient !== undefined){
                 setIdClient(response.idClient);
             }
             if(response.message !== undefined && response.nameOperator !== undefined){
                 received(response.message, response.nameOperator);
             }
        });
        var messages = []
        function sendMessage(text) {
            socket.emit("private message", {message:text,idClient:localStorage.getItem("idClientChatCriaSystem"+removeCharacteres(document.location.host)) || idClient});
        } 