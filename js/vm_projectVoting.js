function changeRating(id,operation) {
    removeElementsByClass('error-label');
    var value = document.getElementById('rating_'+id).value;
    if (!Number.isInteger(parseInt(value))) {
        var error = document.createElement("div");
        error.className = 'error-label alert alert-warning';
        error.innerHTML = 'Nezadal(a) jste číslo.';
        var inputGroup = document.getElementById('input-group-project_'+id);
        var post = document.getElementById('project_'+id);
        insertAfter(inputGroup, error);
        post.scrollIntoView({
                behavior: 'smooth',
                block: 'start'});
        showConfirmButton();

        return false;
    }

    if (operation == 'add') {
        document.getElementById('rating_'+id).value++;
    }
    else {
        document.getElementById('rating_'+id).value--; 
    }
}

function confirmUserVote() {
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
   document.getElementById('vm_successSection').style.display = 'none';
}

function cancelUserVote() {
    var confirmSections = document.getElementsByClassName('confirmSection');
    for (var i=0, n=confirmSections.length; i < n; ++i) {
        confirmSections[i].style.display = 'none';
    }
    var ratings = document.getElementsByClassName("rating");
    for (var i=0, n=ratings.length; i < n; ++i) {
        ratings[i].value=0;
    }
    var confirmButtons = document.getElementsByClassName("confirmUserVote");
    for (var i=0, n=confirmButtons.length; i < n; ++i) {
        confirmButtons[i].style.display = 'block';
    }   
}


function showConfirmButton () {
    var confirmButtons = document.getElementsByClassName("confirmUserVote");
    for (var i=0, n=confirmButtons.length; i < n; ++i) {
        confirmButtons[i].style.display = 'block';
    }
}

function appendAlertToItem(ratingObject, text) {
            var error = document.createElement("div");
            error.className = 'error-label alert alert-danger';
            error.innerHTML = text;
            projectId = ratingObject.id.replace('rating_','');
            var inputGroup = document.getElementById('input-group-project_'+projectId);
            var post = document.getElementById('project_'+projectId);
            insertAfter(inputGroup, error);
            post.scrollIntoView({
                behavior: 'smooth',
                block: 'start'});
            showConfirmButton();
}

function showAlertInSidebar(text) {
    var sidebarAlerts = document.getElementsByClassName("vm_sidebar-alert");
        for (var i=0, n=sidebarAlerts.length; i < n; ++i) {
             sidebarAlerts[i].innerHTML = text;   
             sidebarAlerts[i].style.display = 'block';
        }
    
}

function checkRating() {
    removeElementsByClass('error-label');
    var confirmSections = document.getElementsByClassName('confirmSection');

    for (var i=0, n=confirmSections.length; i < n; ++i) {
        confirmSections[i].style.display = 'none';   
    }

    var ratings = document.getElementsByClassName("rating");
    var positiveRatings = {};
    var negativeRatings = {};
    for (var i=0, n=ratings.length; i < n; ++i) {
        if (Number.isInteger(parseInt(ratings[i].value))){
            if (ratings[i].value>0){
                positiveRatings[ratings[i].id]=ratings[i].value;
                
                if (ratings[i].value>2) {
                    appendAlertToItem(ratings[i], 'Pouze 2 kladné hlasy na projekt.');
                    return false;    
                }

            }
            else if (ratings[i].value!=0) 
            {   negativeRatings[ratings[i].id]=ratings[i].value;

                if (ratings[i].value<=-2) {
                    appendAlertToItem(ratings[i], 'Pouze 1 záporný hlas na projekt.');
                    return false;    
                }
            }
        }
        else {
            appendAlertToItem(ratings[i], 'Nezadal(a) jste číslo.')
            return false;
        }

    }

    var positiveValues = Object.keys(positiveRatings).map(function(e) {
        return positiveRatings[e]
    });
    var negativeValues = Object.keys(negativeRatings).map(function(e) {
        return negativeRatings[e]
    });

    
    // nic nevyplneno
    if (!Object.keys(positiveRatings).length && !Object.keys(negativeRatings).length)   
    {   
       showAlertInSidebar('Neudělil(a) jste žádný hlas.');
       showConfirmButton();

        return false; 
    }

    // max 5 kladnych
    if (Object.keys(positiveRatings).length && positiveValues.reduce(sumValues)>5)   
    {   
        showAlertInSidebar('Rozdal(a) jste více než 5 kladných hlasů.');
        showConfirmButton();
        return false; 
           
    }
    // na 1 zaporny, 2 kladne hlasy
    if (Object.keys(positiveRatings).length && positiveValues.reduce(sumValues)<4 && Object.keys(negativeRatings).length && negativeValues.reduce(sumValues)==-2) 
    {   
        showAlertInSidebar('2 záporné hlasy můžete rozdat pouze, když udělíte 4 kladné.');
        showConfirmButton();
        return false;
    }
    // max 2 zaporne
    if (Object.keys(negativeRatings).length && negativeValues.reduce(sumValues)<-2)   
    {   
        showAlertInSidebar('Rozdal(a) jste více než 2 záporné hlasy.');
        showConfirmButton();
        return false; 
    }

    var allRatings = Object.assign(positiveRatings, negativeRatings);
    saveRating(allRatings);

}

function saveRating(allRatings) {
    allRatings = JSON.stringify(allRatings); 
    var newUserVote = document.getElementById('vm_newUserVote');
    showConfirmButton();
    cancelUserVote()

    var request = new XMLHttpRequest();

    request.open('POST', checkUserVoteAjax.ajax_url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            // If successful       
            var data = JSON.parse(this.response);
            var votedProjects = Object.keys(data.data).map(function(e) {
                return data.data[e]
            });
            newUserVote.innerHTML='';
            document.getElementById('vm_successSection').style.display = 'block';

            for (var i=0, n=votedProjects.length; i < n; ++i) {
                var userRating = votedProjects[i][0];
                var projectTitle = votedProjects[i][1];
                newUserVote.innerHTML+='<strong>' + projectTitle + '</strong>, hodnocení:&nbsp;<strong>' + userRating + '</strong><br />';
            }
            
        } else {
            // If fail
            newUserVote.innerHTML='Došlo k chybě, prosím opakujte hlasování.';
        }
    };
    request.onerror = function() {
        // Connection error
        newUserVote.innerHTML='Došlo k chybě, prosím opakujte hlasování.';
    };
    //request.responseType='json';
    request.send('action=checkUserVoteAjax&userRatings='+allRatings);
}

function removeElementsByClass(className){
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function insertAfter(referenceNode, newNode) {
referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function validRating(element) {
    var values = [];
    values.push(Number(element.value));

}

function sumValues(total, num) {
    return parseInt(total) + parseInt(num);
}

if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
      value: function assign(target, varArgs) { // .length of function is 2
        'use strict';
        if (target == null) { // TypeError if undefined or null
          throw new TypeError('Cannot convert undefined or null to object');
        }
  
        var to = Object(target);
  
        for (var index = 1; index < arguments.length; index++) {
          var nextSource = arguments[index];
  
          if (nextSource != null) { // Skip over if undefined or null
            for (var nextKey in nextSource) {
              // Avoid bugs when hasOwnProperty is shadowed
              if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                to[nextKey] = nextSource[nextKey];
              }
            }
          }
        }
        return to;
      },
      writable: true,
      configurable: true
    });
}

Number.isInteger = Number.isInteger || function(value) {
    return typeof value === 'number' && 
      isFinite(value) && 
      Math.floor(value) === value;
  };