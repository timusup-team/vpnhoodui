function getToken(id){
    event.preventDefault()
    if(document.getElementById(id).classList.contains('d-none')){
        getTokenFromServer(id)
    }else{
        document.getElementById(id).classList.add("d-none")
        document.getElementById(id+'_cpbtn').classList.add("d-none")
    }
}

function generateToken(id){
    event.preventDefault()
    if(document.getElementById(id).classList.contains('d-none')){
        getTokenFromServer(id, true)
    }else{
        document.getElementById(id).classList.add("d-none")
        document.getElementById(id+'_cpbtn').classList.add("d-none")
    }
}

function deleteToken(id){
    event.preventDefault()
    deleteTokenFromServer(id)
}
function deleteTokenFromServer(id) {
    let url = baseUrlAll+'?delete=' + id
    fetch(url)
        .then(responce => responce.text())
        .then((x)=> {
            alert('Token Deleted. Refreshing...')
            window.location.reload();
        })
        .catch((e) => {
            document.getElementById(id+'_spinner').classList.add("d-none")
            console.log(e)
        })
}

function getTokenFromServer(id, gen=false){
    let url = baseUrlAll+'?printtoken='+id
    if (gen){
        let tokenName = document.getElementById('tokenName').value
        url = baseUrlAll+'?gen=1&tokenName='+tokenName
    }
    document.getElementById(id+'_spinner').classList.remove("d-none")

    fetch(url)
        .then(responce => responce.text())
        .then(token => showTokenBox(id,token))
        .catch((e) => {
            document.getElementById(id+'_spinner').classList.add("d-none")
            console.log(e)
        })

}

function showTokenBox(id,token){
    document.getElementById(id).innerText = token
    document.getElementById(id+'_spinner').classList.add("d-none")
    document.getElementById(id).classList.remove("d-none")
    document.getElementById(id+'_cpbtn').classList.remove("d-none")
}

function copyText(id) {
    // Get the text field
    var copyText = document.getElementById(id);

    // Copy the text inside the text field
    if (!navigator.clipboard){
        unsecuredCopyToClipboard(copyText.innerText)
    }else{
        navigator.clipboard.writeText(copyText.innerText);
    }

    // Alert the copied text
    alert("Copied the text: " + copyText.innerText);
    window.location.reload();

}

function unsecuredCopyToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        console.error('Unable to copy to clipboard', err);
    }
    document.body.removeChild(textArea);
}
