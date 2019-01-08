<template>
    <div class="col-12 col-md-3">
        <a href="#" v-if="announcementAdminMode" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        <h5 class="text-center dwhite">Обявления:</h5>
        <div v-for="announcement in announcements" :key="announcement.id">
            <div class="mt-3 announcement">
                <a href="#" @click.prevent="editAnnouncement(announcement)">
                    <p class="mb-0 float-left dwhite"><img :class="'img-fluid w-50 p-1 float-left'" :src="'/storage/news/' + announcement.photos" :alt="announcement.title">{{ announcement.title }}</p>
                    <div v-if="!announcementAdminMode" class="p-1 d-inline-block w-100">
                        <hr class="mb-0 mt-0">
                        <p  class="mb-0 mt-0 ml-1 float-left dwhite">Виж още</p>
                        <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ announcement.date }}</h5>
                    </div>
                </a>
                <div v-if="announcementAdminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
                    <a class="float-left" href="#" @click.prevent="editAnnouncement(announcement)">
                    <i class="fas fa-edit yellow"></i>&#160;</a>
                    <a class="float-left" href="#" @click.prevent="deleteAnnouncement(announcement.id)">
                    <i class="fas fa-trash red"></i>&#160;</a>
                    <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ announcement.date }}</h5>
                </div>
            </div>
        </div>

        <div class="modal fade" id="announcementModal" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div v-if="announcementAdminMode" class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="announcementModalLabel">Добавяне на обявление</h5>
                        <h5 v-if="editMode" class="modal-title" id="announcementModalLabel">Промени обявление {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
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
                        <div class="modal-footer">
                            <button type="button" @click="clearForm()" class="btn btn-danger">Изчисти</button>
                            <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                            <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                        </div>
                    </form>
                </div>


                 <div v-if="!announcementAdminMode" class="modal-content for-user">
                    <div class="modal-header">
                        <h5 class="modal-title dwhite" id="exampleModalLabel">{{ this.form.title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/news/' + this.form.photos" :alt="this.form.title">{{ this.form.description }}</p>
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
                announcementAdminMode : false,
                editMode : false,
                announcements : null,
                announcement : null,
                form : new Form({
                    id: null,
                    title : null,
                    photos : null,
                    description : null,
                    date : null,
                    category: 'announcement'
                }),
            }
        },
        methods :{
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
            changeAdminMode() {
                if(this.$gate.isAdmin()) {
                    this.announcementAdminMode = true
                } else {
                    this.announcementAdminMode = false
                }
            },
            getAnnouncements() {
                axios.get("/api/news")
                .then(({ data }) => {this.announcements = data.data})
                .catch(() => {

                });
            },
            showModal(){
                this.editMode = false
                $("#announcementModal").modal("show");
            },
            createAnnouncement(){
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
            editAnnouncement(announcement){
                this.editMode = true;
                $("#announcementModal").modal("show");
                this.form.fill( announcement );
            },
            updateAnnouncement() {
                this.form.patch('/api/news/' + this.form.id)
                .then(() => {
                    $('#announcementModal').modal('hide');
                    this.getAnnouncements();
                })
                .catch(() => {
                })
            },
            deleteAnnouncement(id){
               axios.delete('/api/news/' + id)
               .then(() => {
                    this.getAnnouncements();
                })
                .catch(() => {

                })
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
            this.getAnnouncements();
        }
    }
</script>
