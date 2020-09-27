function ajaxTbodyMatchesFootChamp(idTable, urls)
{
    var table = qs('#'+idTable)
    var url = urls['matches']
    var method = 'POST'

    var championnat_id = qs('#championnat_id').value
    var champ_saison_id = qs('#champ_saison_id').value
    var equipe_id = qs('#equipe_id').value
    var champ_journee_id = qs('#champ_journee_id').value
    var _token = qs('input[name=_token]').value

    // Requète Ajax pour récupérer les matches
    fetch(url, {
        method:method,
        body:JSON.stringify({_token, champ_journee_id, champ_saison_id, championnat_id, equipe_id}),
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
                tr.appendChild(tdCheckbox)

                let journee = res[i]['journee']
                td = dce('td')
                td.innerHTML = journee
                tr.appendChild(td)

                let rencontre = res[i]['nom']
                td = dce('td')
                // rencontre.align = 'left'
                td.innerHTML = rencontre
                tr.appendChild(td)

                let tdActions = dce('td')
                tdActions.align = 'right'
                tdActions.innerHTML = `
                        <a href="/admin/autres/champ-matches/foot/${id}" title="Vue">
                            <button class="btn-sm btn-success">
                                ${BOUTONVUE}
                                <span class="d-none d-xl-inline">Vue</span>
                            </button>
                        </a>
                        <a href="/admin/autres/champ-matches/foot/editer/${id}" title="Editer">
                            <button class="btn-sm btn-info text-white">
                                ${BOUTONEDIT}
                                <span class="d-none d-xl-inline">Édit</span>
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
            triDataTables(idTable)
        }
    })
    .catch(err => cl(err))
}

function listeChampMatches(idTable, selects, urls)
{
    var boutonSupprimerTout = qs('#supprimerSelection')
    boutonSupprimerTout.addEventListener('click', function(e){
        let ok = confirm("Supprimer la selection définitivement ?")
        if(!ok){
            e.preventDefault()
            e.stopPropagation()
        }
        var lesCheckbox = qsa('input[type=checkbox]', qs('#'+idTable+' > tbody'))
        var lesIdsASupprimer = []
        lesCheckbox.forEach(element => {
            let id = element.value
            if(element.checked){
                lesIdsASupprimer.push(id)
            }
        });

        $.ajax({
            url: "<?php echo route('autres') ?>/champ-matches/foot/supprimer",
            method: 'POST',
            data: {delete:lesIdsASupprimer, _token:inputToken.value},
            success: function(data) {
                ajaxTbodyMatchesFootChamp(idTable, urls)
            }
        })
    })

    selects.forEach(select => {
        select.onchange = function(){
            var formAjax = qs('#formAjax')
            var method = 'POST'
            var selectChampionnats = qs('#championnat_id')
            var selectSaisons = qs('#champ_saison_id')
            var selectEquipes = qs('#equipe_id')
            var selectJournees = qs('#champ_journee_id')
            var inputToken = qs('input[name=_token]')
            var table = qs('#'+idTable)
            var tbody = qs('tbody', table);
            var tbodyNew = dce('tbody')

            if(this.id == 'championnat_id'){
                qs('#'+idTable+' tbody').innerHTML = ''
                selectEquipes.innerHTML = ''
                selectJournees.innerHTML = ''
                let donneesRequeteAjax = {
                    url : urls['saisons'],
                    method : method,
                    idSelect : 'champ_saison_id',
                    data : {championnat_id:selectChampionnats.value, _token:inputToken.value}
                }

                ajaxSelect(donneesRequeteAjax) // On récupère la liste des saisons
                table.replaceChild(tbodyNew, tbody)
                triDataTables(idTable)
                return false; // On évite de charger tous les matches d'un même championnat
                            // On le fait que quand on aura la saison
            }

            if(this.id == 'champ_saison_id'){
                qs('#'+idTable+' tbody').innerHTML = ''
                selectEquipes.innerHTML = ''
                selectJournees.innerHTML = ''
                if(this.value.length != 0){
                    let donneesRequeteAjax = {
                        url : urls['journees'],
                        method : method,
                        idSelect : 'champ_journee_id',
                        data : {champ_saison_id:selectSaisons.value, _token:inputToken.value}
                    }
                    // On récupère la liste des journées associées à la saison
                    ajaxSelect(donneesRequeteAjax)

                    let donneesRequeteAjax2 = {
                        url : urls['equipes'],
                        method : method,
                        idSelect : 'equipe_id',
                        data : {champ_saison_id:selectSaisons.value, _token:inputToken.value}
                    }
                    // On récupère la liste des équipes associées à la saison
                    ajaxSelect(donneesRequeteAjax2)
                }else{
                    table.replaceChild(tbodyNew, tbody)
                    triDataTables(idTable)
                    return false;
                }
            }

            ajaxTbodyMatchesFootChamp(idTable, urls)
        }
    })
}
