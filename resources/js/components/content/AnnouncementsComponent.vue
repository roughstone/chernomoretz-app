<!--Displays the contents of each announcements. When the logged in user is administrator the content editing tools are shown. -->
<template>
    <div class="col-12 col-md-3">
<!--The following <div> is shown only if "adminMode" parameter is set to true.
    The click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
        <div v-if="adminMode" class="col-12">
            <a href="#" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        </div>
        <h5 class="text-center dwhite">Обявления:</h5>
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "announcements" parameter.  -->
        <div v-for="announcement in announcements" :key="announcement.id">
            <div class="mt-3 announcement">
                <a href="#" @click.prevent="editAnnouncement(announcement)">
                    <p class="mb-0 float-left dwhite"><img :class="'img-fluid w-50 p-1 float-left'" :src="'/storage/images/' + announcement.photos" :alt="announcement.title">{{ announcement.title }}</p>
                    <div v-if="!adminMode" class="p-1 d-inline-block w-100">
                        <hr class="mb-0 mt-0">
                        <p  class="mb-0 mt-0 ml-1 float-left dwhite">Виж още</p>
                        <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ announcement.date }}</h5>
                    </div>
                </a>
<!-- The following <div> is shown if adminMode parameter is true (the user is administrator)-->
                <div v-if="adminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "editAnnouncement" method passing the announcement object to it. -->
                    <a class="float-left" href="#" @click.prevent="editAnnouncement(announcement)">
                    <i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "deleteAnnouncement" method passing the announcement id to it. -->
                    <a class="float-left" href="#" @click.prevent="deleteAnnouncement(announcement.id)">
                    <i class="fas fa-trash red"></i>&#160;</a>
                    <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ announcement.date }}</h5>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                    <a :href="'/Снимки/' + announcement.photos"><i class="fas fa-cut blue"></i>&#160;</a>
                </div>
            </div>
        </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
        <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
<!--The following <div> is shown if adminMode parameter is true (the user is administrator)-->
                <div v-if="adminMode" class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="announcementModalLabel">Добавяне на обявление</h5>
                        <h5 v-if="editMode" class="modal-title" id="announcementModalLabel">Промени обявление {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
<!-- The following <form> when submitted calls "updateAnnouncement" or "createAnnouncement" method based of the editMode parameter condition.-->
                    <form @submit.prevent="editMode ? updateAnnouncement() : createAnnouncement()" id="announcementForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Заглавие:</label>
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Дата на обявлението:</label>
                                <input v-model="form.date" type="date" name="date"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('date') }" placeholder="Дата" required>
                                <has-error :form="form" field="date"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Снимка:</label>
                             <input type="file" @change="onFileSelect" name="photos" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Описание:</label>
                                <textarea v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Описание" required></textarea>
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
                        <h5 class="modal-title dwhite" id="exampleModalLabel">{{ this.form.title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/images/' + this.form.photos" :alt="this.form.title">{{ this.form.description }}</p>
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
                editMode : false, // swich the edit mode
                announcements : null, // holds the records from the DB
                form : new Form({ //instantiate a new Form object
                    id: null, // id of DB record
                    title : null, // title of DB record
                    photos : null, // photos of DB record
                    description : null, // description of DB record
                    date : null, // date of DB record
                    category: 'announcement' // category of DB record set to 'announcement' because the DB holds 'news' category too.
                }),
            }
        },
        methods :{
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
            changeAdminMode() { //check is the user administrator
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getAnnouncements() { //request to the backend to get the records
                axios.get("/api/news")
                .then(( data ) => {this.announcements = data.data})
                .catch(() => {

                });
            },
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#announcementModal").modal("show");
            },
            createAnnouncement(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/news')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте ново обявление!'});
                        $('#announcementModal').modal('hide');
                        this.getAnnouncements();
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editAnnouncement(announcement){ // display the form to edit DB records
                this.editMode = true;
                $("#announcementModal").modal("show");
                this.form.fill( announcement );
            },
            updateAnnouncement() { // request to the backend to edit a specific record
                if(this.$gate.isAdmin()) {
                    this.form.patch('/api/news/' + this.form.id)
                    .then(() => {
                        $('#announcementModal').modal('hide');
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getAnnouncements();
                    })
                    .catch(() => {
                    })
                }
            },
            deleteAnnouncement(id){ // request to the backend to delete a record
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
                            axios.delete('/api/news/' + id)
                            .then(() => {
                                this.getAnnouncements();
                            })
                            .catch(() => {

                            })
                        }
                    })
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
            this.getAnnouncements();
        }
    }
</script>
