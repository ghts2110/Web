function changeText(){
    document.getElementById("first_text").innerHTML = "uhu, Você alterou o parágrafo!"
}

function alerta(){
    alert('Você foi alertado!!!!')
}

function azul(){
    document.bgColor = "blue";
}

function url(){
    alert(location)
}

function voltar(){
    history.back()
}

function cook(){
    var bool = navigator.cookieEnabled;
    if(bool){
        alert("cookie habilitado")
    }else{
        alert("cookie nao habilitado")

    }
}

function cor(){
    document.getElementById("first_text").style.color = "red"
}

function preto(){
    document.getElementById("first_text").style.color = "black"
}

function keyDownHandler() {
    alert("Tecla pressionada: " + event.key);
}

