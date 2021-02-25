function confirmUserVote () {
    var confirmButtons = document.getElementsByClassName("confirmUserVote");
    for (var i=0, n=confirmButtons.length; i < n; ++i) {
        confirmButtons[i].style.display = 'none';
    }

   var confirmSections = document.getElementsByClassName('confirmSection');
   for (var i=0, n=confirmSections.length; i < n; ++i) {
       confirmSections[i].style.display = 'block';
   }

   var sidebarAlerts = document.getElementsByClassName("vm_sidebar-alert");
   for (var i=0, n=sidebarAlerts.length; i < n; ++i) {
        sidebarAlerts[i].style.display = 'none'; 
   }

   if (document.getElementsByClassName("vm_voted").length == 0)
   {
        var warning = document.getElementById("vm_alert-warning");
        warning.innerHTML = "Nehlasoval(a) jste pro žádný projekt, Váš hlas se po uložení anuluje.";
        warning.style.display = 'block';
   }

   document.getElementById('vm_newUserVote').style.display = 'none';
   document.getElementById('vm_successSection').style.display = 'none';
   document.getElementById('vm_warningSection').style.display = 'none';
}

function cancelUserVote () {
    var confirmSections = document.getElementsByClassName('confirmSection');
    for (var i=0, n=confirmSections.length; i < n; ++i) {
        confirmSections[i].style.display = 'none';
    }
    var voteButtons = document.getElementsByClassName("vm_voteButton");
    for (var i=0, n=voteButtons.length; i < n; ++i) {
        voteButtons[i].classList.add("btn-success");
        voteButtons[i].classList.remove("btn-secondary", "vm_voted");
        voteButtons[i].innerHTML = "Vybrat";
    }
    var confirmButtons = document.getElementsByClassName("confirmUserVote");
    for (var i=0, n=confirmButtons.length; i < n; ++i) {
        confirmButtons[i].style.display = 'block';
    }

    var sidebarAlerts = document.getElementsByClassName("vm_sidebar-alert");
    for (var i=0, n=sidebarAlerts.length; i < n; ++i) {
        sidebarAlerts[i].style.display = 'none'; 
    }
    
    document.getElementById('vm_newUserVote').style.display = 'block';
}

function changeVote (id) 
{   
    var button = document.getElementById('voteButton_'+id);

    var sidebarAlerts = document.getElementsByClassName("vm_sidebar-alert");
    for (var i=0, n=sidebarAlerts.length; i < n; ++i) {
        sidebarAlerts[i].style.display = 'none'; 
    }

    if (button.classList.contains("vm_voted")) 
    {   
        document.getElementById('vm_newUserVote').style.display = 'block';
        button.classList.add("btn-success");
        button.classList.remove("btn-secondary", "vm_voted");
        button.innerHTML = "Vybrat";
    } else {
        document.getElementById('vm_newUserVote').style.display = 'none';
        button.classList.remove("btn-success");
        button.classList.add("btn-secondary", "vm_voted");
        button.innerHTML = "Zrušit";
    }
}

function checkVote ()
{   
    removeElementsByClass('error-label');
    var voteButtons = document.getElementsByClassName("vm_voteButton");
    var allVotes = {};
    var allowedVotesCount = parseInt(document.getElementById("vm_allowedVotesCount").innerHTML);
    var voteCountExceededEcho = document.getElementById("vm_voteCountExceededEcho").innerHTML;

    for (var i=0, n=voteButtons.length; i < n; ++i) {

        if (voteButtons[i].classList.contains("vm_voted")) 
        {
            allVotes[voteButtons[i].id]=1;
        }
    }

    if (Object.keys(allVotes).length > allowedVotesCount)
    {
        var danger = document.getElementById("vm_alert-danger");
        danger.innerHTML = voteCountExceededEcho;
        danger.style.display = 'block';
        return false; 
    }    

    if (!Object.keys(allVotes).length) 
    {
        //nehlasoval jste pro zadny projekt, vas hlas bude tedy vyjadrovat NESOUHLAS
    }

    saveVote(allVotes);

}

function removeElementsByClass (className)
{
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function showConfirmButton () {
    var confirmButtons = document.getElementsByClassName("confirmUserVote");
    for (var i=0, n=confirmButtons.length; i < n; ++i) {
        confirmButtons[i].style.display = 'block';
    }
}

function saveVote (allVotes) {
    allVotes = JSON.stringify(allVotes); 
    var newUserVote = document.getElementById('vm_newUserVote');

    var request = new XMLHttpRequest();

    request.open('POST', saveUserVoteAjax.ajax_url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    request.onload = function () {
        if (this.status == 200) 
        {   
            var data = JSON.parse(this.response);
            
            if (data.data == "voteInvalidated") 
            {
                //vynulovani hlasu
                newUserVote.innerHTML='Hlasování anulováno.';
                document.getElementById('vm_warningSection').style.display = 'block';

            } else {
                var votedProjects = Object.keys(data.data).map(function(e) {
                    return data.data[e]
                });
                newUserVote.innerHTML='';
                document.getElementById('vm_successSection').style.display = 'block';

                for (var i=0, n=votedProjects.length; i < n; ++i) {
                    var userRating = votedProjects[i][0];
                    var projectTitle = votedProjects[i][1];
                    newUserVote.innerHTML+='Projekt <strong>' + projectTitle + '</strong>, 1 hlas<br />';
                }
            }

            var confirmButtons = document.getElementsByClassName("confirmUserVote");
            for (var i=0, n=confirmButtons.length; i < n; ++i) 
            {
                confirmButtons[i].innerHTML = 'Změnit hlasování';
            }
            
        } else {
            // neuspesna
            newUserVote.innerHTML='Došlo k chybě, prosím opakujte hlasování.';
        }
        
        showConfirmButton();
        cancelUserVote();
        document.getElementById('vm_loadingAnimation').style.display = 'none';
        document.getElementById('vm_voteStateSection').style.display = 'block';
    };
    request.onerror = function() {
        // chyba spojeni
        newUserVote.innerHTML='Došlo k chybě, prosím opakujte hlasování.';
    };
    
    document.getElementById('vm_loadingAnimation').style.display = 'block';
    document.getElementById('vm_voteStateSection').style.display = 'none';

    setTimeout(function(){ 
                request.send('action=saveUserVoteAjax&userVote='+allVotes);
    }, 900);
    
}
