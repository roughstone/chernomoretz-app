<!--Displays the contents of each competitors. When the logged in user is administrator the content editing tools are shown. -->
<template>
<div class="row flex-xl-nowrap">
   <div class="col-12">
<!--The following <div> is shown only if "adminMode" parameter is set to true.
    The click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
        <div v-if="adminMode" class="col-12">
            <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        </div>
<!--The following <div> holds the navigation of the news component content. -->
        <div v-if="this.pages > 1" class="page-navigation d-inline-block">
            <div class="page-button float-left d-inline-block">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "prevPage" method. The logic open the previous page of content. -->
                <a class="page-link" @click.prevent="prevPage()"><i class="fas fa-chevron-left"></i></a>
            </div>
            <div class="page-input float-left d-inline-block">
                <p class="page-text">Страница</p>
            </div>
            <div class="page-input float-left d-inline-block">
<!--The following <input> when changed calls "getPage" method. The logic open page based on user input. -->
                <input class="page-link" maxlength="2" size="2" type="text" @keyup="getPage()" v-model="page" name="page">
            </div>
            <div class="page-input float-left d-inline-block">
                <p class="page-text">от: {{ this.pages }}</p>
            </div>
            <div class="page-button float-left d-inline-block">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "nextPage" method. The logic open the next page of content. -->
                <a class="page-link" @click.prevent="nextPage()"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "competitors" parameter.  -->
        <div v-for="competitor in competitors" :key="competitor.id" :class="'pt-0 col-12 col-md-6 col-lg-4 float-left competitors'">
            <div class="competitor d-inline-block">
                <a href="#" @click.prevent="editCompetitor(competitor)" class="dwhite">
                    <div class="col-6 mt-2 float-left">
                        <img :class="'img-fluid'" :src="'/storage/images/' + competitor.photos" :alt="competitor.title">
                    </div>
                    <div class="col-3 pr-0 pl-0 float-left mt-3">
                        <p class="text-right">Име:&#160;</p>
                        <p class="text-right">Презиме:&#160;</p>
                        <p class="text-right">Години:&#160;</p>
                    </div>
                    <div class="col-3 pr-0 pl-0 float-left mt-3">
                        <p class="text-left">{{ competitor.firstName }}</p>
                        <p class="text-left">{{ competitor.lastName }}</p>
                        <p class="text-left">{{ competitor.birthday | getAge }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-center h6 mb-0 underline">Състезател по</p>
                        <p class="text-center h4">{{ competitor.sport }}</p>
                    </div>
                </a>
<!-- The following <div> is shown if adminMode parameter is false (the user is not administrator)-->
                <div v-if="adminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "editCompetitor" method passing the competitor object to it. -->
                    <a href="" @click.prevent="editCompetitor(competitor)"><i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "deleteCompetitor" method passing the competitor id to it. -->
                    <a href="" @click.prevent="deleteCompetitor(competitor.id)"><i class="fas fa-trash red"></i>&#160;</a>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                    <a :href="'/Снимки/' + competitor.photos"><i class="fas fa-cut blue"></i>&#160;</a>
                </div>
            </div>
        </div>
   </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
   <div class="modal fade" id="competitorsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
<!--The following <div> is shown if adminMode parameter is true (the user is administrator)-->
            <div v-if="adminMode" class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на състезател</h5>
                    <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на състезател {{ this.form.id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
<!-- The following <form> when submitted calls "updateCompetitor" or "createCompetitor" method based of the editMode parameter condition.-->
                <form @submit.prevent="editMode ? updateCompetitor() : createCompetitor()" id="newsForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Име:</label>
                            <input v-model="form.firstName" type="text" name="firstName"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('firstName') }" placeholder="Име" required>
                            <has-error :form="form" field="firstName"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Фамилия:</label>
                            <input v-model="form.lastName" type="text" name="lastName"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('lastName') }" placeholder="Фамилия" required>
                            <has-error :form="form" field="lastName"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Рожданна дата:</label>
                            <input v-model="form.birthday" type="date" name="birthday"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('birthday') }" placeholder="Дата" required>
                            <has-error :form="form" field="birthday"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Снимка:</label>
                            <input type="file" @change="onFileSelect" name="photos" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Спорт:</label>
                            <input v-model="form.sport" type="text" name="sport"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('sport') }" placeholder="Позиция" required>
                            <has-error :form="form" field="sport"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Автобиография:</label>
                            <textarea v-model="form.description" type="text" name="description"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Автобиография" required></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>
                    </div>
<!-- The following <div> holds buttons based on editMode parameter condition and button to clear the form with that call clearForm method -->
                    <div class="modal-footer">
                        <button type="button" @click="clearForm()" class="btn btn-danger">Изчисти</button>
                        <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                        <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                    </div>
                </form>
            </div>
<!--The following <div> is shown if adminMode parameter is false (the user is not administrator)-->
            <div v-if="!adminMode" class="modal-content for-user">
                <div class="modal-header">
                    <h5 class="modal-title w-100 dwhite text-center">{{ this.form.firstName }} {{ this.form.lastName}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="competitor-description dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/images/' + this.form.photos" :alt="this.form.firstName +' '+ this.form.lastName">{{ this.form.description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                adminMode : false, // swich to show administrator content
                competitors : null, // holds the records from the DB
                editMode : false, // swich the edit mode
                page: 1, //current page
                pages: null, //numer of pages
                form : new Form ({ //instantiate a new Form object
                    id : null, // id of DB record
                    firstName : null, // firstName of DB record
                    lastName : null, // lastName of DB record
                    birthday : null, // birthday of DB record
                    photos : null, // photos of DB record
                    sport: null, // sport of DB record
                    description : null, // description of DB record
                }),
            }
        },
        methods: {
            clearForm() { // clear the form based on editMode parameter condition keep data
                if (this.editMode) {
                    this.form.originalData.id = this.form.id
                    this.form.originalData.photos = this.form.photos
                    this.form.reset();
                } else if (!this.editMode){
                    this.form.originalData.id = null
                    this.form.originalData.photos = null
                    this.form.reset();
                }
            },
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#competitorsModal").modal("show");
            },
            getCompetitors() { //request to the backend to get the records
                axios.get("/api/competitors?page="+ this.$route.params.page)
                .then(({ data }) => {
                    this.competitors = data.data
                    this.pages = data.last_page
                })
                .catch(() => {

                });
            },
            nextPage() { // call getPage method for next page
                if (this.page < this.pages) {
                this.page++
                this.getPage()
                }
            },
            prevPage() { //call getPage method for previous page
                if (this.page > 1) {
                    this.page--
                    this.getPage()
                }
            },
            getPage() { //based on user nextPage, prevPage methods or user input calls getCompetitors method to send request to backend with the page number
                this.$route.params.page = this.page
                 if (history.pushState) {
                        let url = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        let slicedUrl = url.slice(0, url.lastIndexOf('/'))
                        let newUrl = slicedUrl + '/' + this.$route.params.page
                        window.history.pushState({path:newUrl},'',newUrl);
                        this.createCompetitor()
                    }
            },
            createCompetitor(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/competitors')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов треньор!'})
                        $('#competitorsModal').modal('hide');
                        this.getCompetitors()
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editCompetitor(data){ // display the form to edit DB records
                this.editMode = true;
                $("#competitorsModal").modal("show");
                this.form.fill( data );
            },
            updateCompetitor() { // request to the backend to edit a specific record
                if(this.$gate.isAdmin()) {
                    this.form.patch('/api/competitors/' + this.form.id)
                    .then(() => {
                        $('#competitorsModal').modal('hide');
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getCompetitors();
                    })
                    .catch(() => {
                    })
                }
            },
            deleteCompetitor (id) { // request to the backend to delete a record
                if(this.$gate.isAdmin()) {
                swal({
                    title: 'Изтриване!',
                    text: `Сигорни ли сте?`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Не, недей!',
                    confirmButtonText: 'Да, изтрий го!'
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/api/competitors/' + id)
                            .then(() => {
                                this.getCompetitors();
                            })
                            .catch(() => {

                            })
                        }
                    })
                }
            },
            changeAdminMode() { //check is the user administrator
                    if(this.$gate.isAdmin()) {
                        this.adminMode = true
                    } else {
                        this.adminMode = false
                    }
            },
            onFileSelect(event) { //instantiate new FileReader object for the selected file
                let file = event.target.files[0];
                this.form.photos = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.form.photos = reader.result;
                }
                reader.readAsDataURL(file)
            },
        },
        mounted() {
            this.changeAdminMode();
            this.getCompetitors();
        }
    }
</script>
