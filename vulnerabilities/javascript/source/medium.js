function do_something(e){for(var t="",n=e.length-1;n>=0;n--)t+=e[n];return t}
setTimeout(function(){do_elsesomething("XX")},300);
function do_elsesomething(e){
    var phrase = document.getElementById("phrase").value;
    var sanitizedPhrase = phrase
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    document.getElementById("token").value = do_something(e + sanitizedPhrase + "XX")
}