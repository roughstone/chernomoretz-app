<template>
    <div class="row flex-xl-nowrap">
        <div class="col-12 pl-0 pr-0">
            <div v-if="adminMode" class="col-12">
                <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
            </div>
            <h2 class="text-center dwhite">Галерия: {{this.$route.params.title}}</h2>
            <div>
                <div v-for="(photo, index) in displayedGallery" :key="photo.photo">
                    <div class="photoGallery float-left" @click="singlePhotoModal(index)" :style="{ 'background-image': 'url(/storage/galleries/' + photo.photos + ')' }">
                        <a v-if="adminMode && index > 0" href="" @click.prevent="deletePhoto(photo.id)">
                            <i class="fas fa-trash red float-right fa-2x"></i>&#160;</a>
                    </div>
                </div>
            </div>

            <div :class="'col-12 d-inline-block mt-3 dwhite '" v-if="allPhotos > loadedPhoto">
                <p class="text-center galleryMenu">
                    <span>Общо снимки: {{ allPhotos }}</span><span>Показани: {{ loadedPhoto }}</span><span @click="loadMorePhoto()">Покажи още</span>
                </p>
            </div>

            <div v-if="photoMethod" :class="'pModal'">
                <div class="text-center imgContainer row align-items-center">
                    <div class="col">
                        <img :class="'img-fluid mt-5 imgContainer'" :src="'/storage/galleries/' + thePhoto.photos" :alt="thePhoto.photos">
                    </div>
                </div>
                    <div class="downloadButton">
                        <a class="dwhite" :href="'/storage/galleries/' + thePhoto.photos" download>Изтегли <i class="fas fa-file-download"></i></a>
                    </div>
                    <div class="closeButton">
                        <a @click.prevent="singlePhotoModal()"><i class="fas fa-times dwhite fa-3x"></i></a>
                    </div>
                    <div class="rightButton">
                        <a @click.prevent="nextPhoto()"><i class="fas fa-chevron-right dwhite fa-3x"></i></a>
                    </div>
                    <div class="leftButton">
                        <a @click.prevent="prevPhoto()"><i class="fas fa-chevron-left dwhite fa-3x"></i></a>
                    </div>

            </div>


            <div class="modal fade" id="photosModal" tabindex="-1" role="dialog" aria-labelledby="photosModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div v-if="adminMode" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="photosModalLabel">Добавяне на галерия</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit.prevent="createPhoto('this')" id="photosForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Заглавие:</label>
                                    <input v-model="form.title" type="text" name="title"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие">
                                    <has-error :form="form" field="title"></has-error>
                                </div>
                                <div class="form-group">
                                    <label>Снимка:</label>
                                    <input type="file" @change="onFileSelect" name="photos" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                            </div>
                        </form>
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
                editMode : false,
                photoMethod : false,
                gallery: null,
                displayedGallery: null,
                photoModal : null,
                photoIndex : null,
                thePhoto : null,
                allPhotos : null,
                loadedPhoto : 16,
                form : new Form ({
                    title : null,
                    photos : null,
                }),
            }
        },
        methods: {
            showModal(){
                this.editMode = false
                $("#photosModal").modal("show");
            },
            changeAdminMode() {
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getPhotos() {
                axios.get("/api/gallery/"+ this.$route.params.id)
                .then(({ data }) => {
                    console.log(data)
                    this.gallery = data
                    this.allPhotos = data.length
                    this.displayGallery()
                })
                .catch(() => {

                });
            },
            createPhoto(){
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/gallery/' + this.$route.params.id)
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов треньор!'})
                        $('#photosModal').modal('hide');
                        this.getPhotos()
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            deletePhoto (id) {
                if(this.$gate.isAdmin()) {
                    axios.delete('/api/gallery/' + id)
                    .then(() => {
                        this.getPhotos();
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
            displayGallery() {
                this.displayedGallery = this.gallery.slice(0 , this.loadedPhoto)
            },
            singlePhotoModal(index) {
                if(!this.photoMethod) {
                    this.photoIndex = index
                    this.photoMethod = true
                    this.thePhoto = this.gallery[this.photoIndex]
                } else {
                    this.photoMethod = false
                    this.photoIndex = null
                    this.thePhoto = null
                }
            },
            nextPhoto() {
                if(this.photoIndex < this.gallery.length - 1) {
                    this.photoIndex += 1
                    this.thePhoto = this.gallery[this.photoIndex]
                } else {
                    this.photoIndex = 0
                    this.thePhoto = this.gallery[this.photoIndex]
                }
            },
            prevPhoto() {
                if(this.photoIndex >= 1) {
                    this.photoIndex -= 1
                    this.thePhoto = this.gallery[this.photoIndex]
                } else {
                    this.photoIndex = this.gallery.length - 1
                    this.thePhoto = this.gallery[this.photoIndex]
                }
            },
            loadMorePhoto() {
                this.loadedPhoto +=16
                this.displayGallery()
            }
        },
        mounted() {
            console.log(this.$route.params)
            this.getPhotos()
            this.changeAdminMode()
        }
    }
</script>
