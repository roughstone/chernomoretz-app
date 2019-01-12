<template>
<div class="row flex-xl-nowrap">
   <div class="col-12">
        <div v-if="adminMode" class="col-12">
            <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        </div>
        <div v-if="this.pages > 1" class="page-navigation d-inline-block">
            <div class="page-button float-left d-inline-block">
                <a class="page-link" @click.prevent="prevPage()"><i class="fas fa-chevron-left"></i></a>
            </div>
            <div class="page-input float-left d-inline-block">
                <p class="page-text">Страница</p>
            </div>
            <div class="page-input float-left d-inline-block">
                <input class="page-link" maxlength="2" size="2" type="text" @keyup="getPage()" v-model="page" name="page">
            </div>
            <div class="page-input float-left d-inline-block">
                <p class="page-text">от: {{ this.pages }}</p>
            </div>
            <div class="page-button float-left d-inline-block">
                <a class="page-link" @click.prevent="nextPage()"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
        <div v-for="competitor in competitors" :key="competitor.id" :class="'pt-0 col-12 col-md-4 float-left competitors'">
            <div class="competitor d-inline-block">
                <a href="#" @click.prevent="editCompetitor(competitor)" class="dwhite">
                    <div class="col-md-6 mb-2 mt-2 float-left">
                        <img :class="'img-fluid'" :src="'/storage/competitors/' + competitor.photos" :alt="competitor.title">
                    </div>
                    <div class="col-6 col-md-3 pr-0 float-left mt-3">
                        <p class="text-right h4">Име:&#160;</p>
                        <p class="text-right h4">Презиме:&#160;</p>
                        <p class="text-right h4">Години:&#160;</p>
                    </div>
                    <div class="col-6 col-md-3 pl-0 float-left mt-3">
                        <p class="text-left h4">{{ competitor.firstName }}</p>
                        <p class="text-left h4">{{ competitor.lastName }}</p>
                        <p class="text-left h4">{{ competitor.birthday | getAge }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-center h6 mb-0 underline">Състезател по</p>
                        <p class="text-center h4">{{ competitor.sport }}</p>
                    </div>
                </a>
                <div v-if="adminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
                    <a href="" @click.prevent="editCompetitor(competitor)"><i class="fas fa-edit yellow float-left"></i>&#160;</a>
                    <a href="" @click.prevent="deleteCompetitor(competitor.id)"><i class="fas fa-trash red float-right"></i>&#160;</a>
                </div>
            </div>
        </div>
   </div>



   <div class="modal fade" id="competitorsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div v-if="adminMode" class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на състезател</h5>
                    <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на състезател {{ this.form.id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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
                    <div class="modal-footer">
                        <button type="button" @click="clearForm()" class="btn btn-danger">Изчисти</button>
                        <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                        <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                    </div>
                </form>
            </div>



             <div v-if="!adminMode" class="modal-content for-user">
                <div class="modal-header">
                    <h5 class="modal-title w-100 dwhite text-center">{{ this.form.firstName }} {{ this.form.lastName}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/competitors/' + this.form.photos" :alt="this.form.firstName +' '+ this.form.lastName">{{ this.form.description }}</p>
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
                adminMode : false,
                competitors : null,
                editMode : false,
                form : new Form ({
                    id : null,
                    firstName : null,
                    lastName : null,
                    birthday : null,
                    photos : null,
                    sport: null,
                    description : null,
                }),
                page: 1,
                pages: null,
            }
        },
        methods: {
            clearForm() {
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
            showModal(){
                this.editMode = false
                $("#competitorsModal").modal("show");
            },
            getCompetitors() {
                axios.get("/api/competitors?page="+ this.$route.params.page)
                .then(({ data }) => {
                    this.competitors = data.data
                    this.pages = data.last_page
                })
                .catch(() => {

                });
            },
            nextPage() {
                if (this.page < this.pages) {
                this.page++
                this.getPage()
                }
            },
            prevPage() {
                if (this.page > 1) {
                    this.page--
                    this.getPage()
                }
            },
            getPage() {
                this.$route.params.page = this.page
                 if (history.pushState) {
                        let url = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        let slicedUrl = url.slice(0, url.lastIndexOf('/'))
                        let newUrl = slicedUrl + '/' + this.$route.params.page
                        window.history.pushState({path:newUrl},'',newUrl);
                        this.getCompetitors()
                    }
            },
            createCompetitor(){
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
            editCompetitor(data){
                this.editMode = true;
                $("#competitorsModal").modal("show");
                this.form.fill( data );
            },
            updateCompetitor() {
                this.form.patch('/api/competitors/' + this.form.id)
                .then(() => {
                    $('#competitorsModal').modal('hide');
                    this.getCompetitors();
                })
                .catch(() => {
                })
            },
            deleteCompetitor (id) {
                axios.delete('/api/competitors/' + id)
                .then(() => {
                    this.getCompetitors();
                })
                .catch(() => {

                })
            },
            changeAdminMode() {
                    if(this.$gate.isAdmin()) {
                        this.adminMode = true
                    } else {
                        this.adminMode = false
                    }
            },
            onFileSelect(event) {
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
