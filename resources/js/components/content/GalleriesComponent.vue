<template>
    <div class="row flex-xl-nowrap">
        <div class="col-12 pl-0 pr-0">
            <div v-if="adminMode" class="col-12">
                <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
            </div>
            <div>
                <div v-for="gallery in galleries" :key="gallery.id">
                    <router-link :to=" gallery.category + '/Галерия/' + gallery.title + '/Id/' + gallery.id">
                        <div class="gallery float-left" :style="{ 'background-image': 'url(/storage/galleries/' + gallery.photos + ')' }">
                        <p class="gallery-title dwhite w-100 text-center">Галерия - {{ gallery.title }}</p>
                        <p class="gallery-date dwhite mb-0">Дата: {{ gallery.date }}</p>
                        <div v-if="adminMode">
                            <a href="" @click.prevent="editGalleries(gallery)"><i class="fas fa-edit yellow fa-2x"></i>&#160;</a>
                            <a href="" @click.prevent="deleteGalleries(gallery.id)"><i class="fas fa-trash red fa-2x"></i>&#160;</a>
                        </div>
                    </div>
                   </router-link>
                </div>
            </div>
        </div>

        <div class="modal fade" id="galleriesModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div v-if="adminMode" class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на галерия</h5>
                        <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на галерия {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateGalleries() : createGalleries()" id="newsForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Заглавие:</label>
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Дата:</label>
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



                <div v-if="!adminMode" class="modal-content for-user">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 dwhite text-center">{{ this.form.firstName }} {{ this.form.lastName}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/galleries/' + this.form.photos" :alt="this.form.firstName +' '+ this.form.lastName">{{ this.form.description }}</p>
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
                galleries : null,
                editMode : false,
                form : new Form ({
                    id : null,
                    title : null,
                    date : null,
                    photos : null,
                    category: this.$route.params.type,
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
                $("#galleriesModal").modal("show");
            },
            changeAdminMode() {
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getGalleries() {
                axios.get("/api/galleries/"+ this.$route.params.type)
                .then(({ data }) => {
                    this.galleries = data.data
                    this.pages = data.last_page
                })
                .catch(() => {

                });
            },
            createGalleries(){
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/galleries')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов треньор!'})
                        $('#galleriesModal').modal('hide');
                        this.getGalleries()
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editGalleries(data){
                this.editMode = true;
                $("#galleriesModal").modal("show");
                this.form.fill( data );
            },
            updateGalleries() {
                this.form.patch('/api/galleries/' + this.form.id)
                .then(() => {
                    $('#galleriesModal').modal('hide');
                    this.getGalleries();
                })
                .catch(() => {
                })
            },
            deleteGalleries (id) {
            if(this.$gate.isAdmin()) {
                    axios.delete('/api/galleries/' + id)
                    .then(() => {
                        this.getGalleries();
                    })
                    .catch(() => {

                    })
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
        beforeRouteUpdate (to, from, next) {
            next()
            this.form.category = this.$route.params.type
            this.getGalleries()
        },
        mounted() {
            this.changeAdminMode()
            this.getGalleries()
        },
    }
</script>
