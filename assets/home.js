
const months = ["Jan","Fev","Mars","Avril","Mai","Juin","Jul","Aout","Sept","Oct","Nov","Dec"];




$(document).ready(function () {
    setInterval(() => {
        if (document.getElementById('tchat') != null) {

            function requeteAjax() {
                request = $.ajax({
                    type: 'GET',          //La méthode cible (POST ou GET)
                    url: document.querySelector('.listLink').href, //Script Cible
                    success: (res) => {
                      
                        content = res.messages_content.map((mes,id) => {
                            let time = new Date(res.messages_createdAt[id])
                            let day = time.getDate()
                            let month = months[time.getMonth()];
                            let hours = time.getHours()
                            let minutes = time.getUTCMinutes()
                            if(hours < 10){hours = "0" + hours}
                            if(minutes < 10){minutes = "0" + minutes}
                            time = day + " " + month + " " + hours + ":" + minutes
                        
                            return `<div><div class="badge bg-info">${time}</div> : ${mes}</div>`
                        }).join('')


                  
                           html =  content
                        document.getElementById('tchat').innerHTML = html
                    }
                });
            }

            requeteAjax()

        }
    }, 5000)
})

