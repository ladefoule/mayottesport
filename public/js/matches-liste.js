function ajaxTbodyMatches(idTable, urls)
{
    var table = qs('#'+idTable)
    var url = urls['matches']
    var method = 'POST'

    var sport_id = qs('#sport_id').value
    var competition_id = qs('#competition_id').value
    var saison_id = qs('#saison_id').value
    var equipe_id = qs('#equipe_id').value
    var journee_id = qs('#journee_id').value
    var _token = qs('input[name=_token]').value

    // Requète Ajax pour récupérer les matches
    fetch(url, {
        method:method,
        body:JSON.stringify({_token, journee_id, saison_id, competition_id, sport_id, equipe_id}),
        headers: {"Content-type": "application/json; charset=UTF-8"}
    })
    .then(res => res.json())
    .then(res => {
        if(res.length > 0){
            let tbody = qs('#'+idTable+' tbody') // On selectionne le tbody existant
            var tbodyNew = dce('tbody') // On crée un nouveau tbody avec les données data

            for(let i in res){
                let id = res[i]['id']

                let tr = dce('tr')
                let tdCheckbox = dce('td')
                let checkbox = dce('input')
                checkbox.type = 'checkbox'
                checkbox.value = id
                tdCheckbox.appendChild(checkbox)
                tdCheckbox.className = 'px-1'
                tr.appendChild(tdCheckbox)

                let journee = res[i]['journee_nom']
                td = dce('td')
                td.innerHTML = journee
                td.className = 'px-1'
                tr.appendChild(td)

                let rencontre = res[i]['nom']
                td = dce('td')
                td.innerHTML = rencontre
                td.className = 'px-1'
                tr.appendChild(td)

                let tdActions = dce('td')
                tdActions.align = 'right'
                tdActions.className = 'px-1'
                tdActions.innerHTML = `
                        <a href="${res[i]['href_show']}" title="Voir">
                            <button class="btn-sm btn-success">
                                ${BOUTONVUE}
                                <span class="d-none d-xl-inline">Voir</span>
                            </button>
                        </a>
                        <a href="${res[i]['href_update']}" title="Editer">
                            <button class="btn-sm btn-info text-white">
                                ${BOUTONEDIT}
                                <span class="d-none d-xl-inline">Editer</span>
                            </button>
                        </a>
                        <a href="" title="Supprimer">
                            <button class="btn-sm btn-danger">
                                ${BOUTONSUPPRESSION}
                                <span class="d-none d-xl-inline">Supprimer</span>
                            </button>
                        </a>
                `
                tr.appendChild(tdActions)
                tbodyNew.appendChild(tr)
            }

            table.replaceChild(tbodyNew, tbody)
            triDataTables(appUrl, idTable)
        }
    })
    .catch(err => cl(err))
}

function listeMatches(idTable, selects, urls)
{
    // var boutonSupprimerTout = qs('#supprimerSelection')
    // boutonSupprimerTout.addEventListener('click', function(e){
    //     let ok = confirm("Supprimer la selection définitivement ?")
    //     if(!ok){
    //         e.preventDefault()
    //         e.stopPropagation()
    //     }
    //     var lesCheckbox = qsa('input[type=checkbox]', qs('#'+idTable+' > tbody'))
    //     var lesIdsASupprimer = []
    //     lesCheckbox.forEach(element => {
    //         let id = element.value
    //         if(element.checked){
    //             lesIdsASupprimer.push(id)
    //         }
    //     });

    //     $.ajax({
    //         url: urls['supprimer'],
    //         method: 'POST',
    //         data: {delete:lesIdsASupprimer, _token:inputToken.value},
    //         success: function(data) {
    //             ajaxTbodyMatches(idTable, urls)
    //         }
    //     })
    // })

    selects.forEach(select => {
        select.onchange = function(){
            // var formAjax = qs('#formAjax')
            var method = 'POST'
            var selectSports = qs('#sport_id')
            var selectCompetitions = qs('#competition_id')
            var selectSaisons = qs('#saison_id')
            var selectEquipes = qs('#equipe_id')
            var selectJournees = qs('#journee_id')
            var inputToken = qs('input[name=_token]')
            var table = qs('#'+idTable)
            var tbody = qs('tbody', table);
            var tbodyNew = dce('tbody')

            if(this.id == 'sport_id'){
                qs('#'+idTable+' tbody').innerHTML = ''
                selectEquipes.innerHTML = ''
                selectJournees.innerHTML = ''
                let donneesRequeteAjax = {
                    url : urls['competitions'],
                    method : method,
                    idSelect : 'competition_id',
                    data : {sport_id:selectSports.value, _token:inputToken.value}
                }

                ajaxSelect(donneesRequeteAjax) // On récupère la liste des competitions
                table.replaceChild(tbodyNew, tbody)
                triDataTables(appUrl, idTable)
                return false; // On évite de charger tous les matches d'un même sport
                            // On le fait que quand on aura la saison
            }

            if(this.id == 'competition_id'){
                qs('#'+idTable+' tbody').innerHTML = ''
                selectEquipes.innerHTML = ''
                selectJournees.innerHTML = ''
                let donneesRequeteAjax = {
                    url : urls['saisons'],
                    method : method,
                    idSelect : 'saison_id',
                    data : {competition_id:selectCompetitions.value, _token:inputToken.value}
                }

                ajaxSelect(donneesRequeteAjax) // On récupère la liste des saisons
                table.replaceChild(tbodyNew, tbody)
                triDataTables(appUrl, idTable)
                return false; // On évite de charger tous les matches d'un même championnat
                            // On le fait que quand on aura la saison
            }

            if(this.id == 'saison_id'){
                qs('#'+idTable+' tbody').innerHTML = ''
                selectEquipes.innerHTML = ''
                selectJournees.innerHTML = ''
                if(this.value.length != 0){
                    let donneesRequeteAjax = {
                        url : urls['journees'],
                        method : method,
                        idSelect : 'journee_id',
                        data : {saison_id:selectSaisons.value, _token:inputToken.value}
                    }
                    // On récupère la liste des journées associées à la saison
                    ajaxSelect(donneesRequeteAjax)

                    let donneesRequeteAjax2 = {
                        url : urls['equipes'],
                        method : method,
                        idSelect : 'equipe_id',
                        data : {saison_id:selectSaisons.value, _token:inputToken.value}
                    }
                    // On récupère la liste des équipes associées à la saison
                    ajaxSelect(donneesRequeteAjax2)
                }else{
                    table.replaceChild(tbodyNew, tbody)
                    triDataTables(appUrl, idTable)
                    return false;
                }
            }

            ajaxTbodyMatches(idTable, urls)
        }
    })
}
