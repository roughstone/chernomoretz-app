<!--Displays the contents of each gallery with the image set as background for better styling.
    Each of those elements has click function wich display the content as a single full screan element.
    When the logged in user is administrator the content editing tools are shown. -->
<template>
    <div class="row flex-xl-nowrap">
        <div class="col-12 pl-0 pr-0">
<!--The following <div> is shown only if "adminMode" parameter is set to true.
    He holds <a> with a click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
            <div v-if="adminMode" class="col-12">
                <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
            </div>

            <h2 class="text-center dwhite">Галерия: {{this.$route.params.title}}</h2><!-- Display the router parameter title -->

<!--The following <div> is multiplied by Vuejs v-for directive for each record form "displayedGallery" parameter.
Size is set to "col-3" grid proportion with zero padding to show 4 records per row.  -->
            <div class="col-3 d-inline-block pl-0 pr-0" v-for="(photo, index) in displayedGallery" :key="photo.photo">
<!--The following <div> has bind inline style background-image with the image name got from the DB -->
                <div class="photoGallery" @click="singlePhotoModal(index)" :style="{ 'background-image': 'url(/storage/images/' + photo.photos + ')' }">
                </div>
<!--The following <a> checks is the user an administrator and is the element the second record from the DB.
    if it is the <a> is displayed on the document with click event that calls "deletePhoto" passing the ID of the record. -->
                <a v-if="adminMode && index > 0" href="" @click.prevent="deletePhoto(photo.id)">
                        <i class="fas fa-trash red fa-lg"></i>&#160;</a>
<!--The following <a> checks is the user an administrator if it is, the <a> is displayed on the document.
    The binding href attribute is a path with the name of the image file to the Vuejs component which can edit it. -->
                <a :href="'/Снимки/' + photo.photos" v-if="adminMode"><i class="fas fa-cut blue fa-lg"></i>&#160;</a>
            </div>
<!--The following <div> is displayed only if the records from the DB are more than the integer provided in the "loadedPhoto" parameter. -->
            <div class="col-12 d-inline-block mt-3 dwhite" v-if="allPhotos > loadedPhoto">
<!--The following <div> center  the <span> elements. The "galleryMenu" class is added for easier styling -->
                <div class="text-center galleryMenu">
                    <span>Общо снимки: {{ allPhotos }}</span><!-- Display the number of all records. -->
                    <span>Показани: {{ loadedPhoto }}</span><!-- Display the number of shown records -->
                    <span @click="loadMorePhoto()">Покажи още</span><!-- Call loadMorePhoto method -->
                </div>
            </div>
<!--The following <div> is shown only if "photoMethod" parameter is set to true, "pModal" class is added for styling. -->
            <div v-if="photoMethod" :class="'pModal'">
<!--The following <div> center it's content -->
                <div class="text-center imgContainer row align-items-center">
                    <div class="col">
<!--The following <img> is responsive the binded src attribute to the image folder and file with title from "thePhoto" object "photos" field.-->
                        <img class="img-fluid mt-5" :src="'/storage/images/' + thePhoto.photos" :alt="thePhoto.photos">
                    </div>
                </div>
                    <div class="downloadButton"><!-- Class "downloadButton" added for styling. -->
<!--The following <a> has binded href attribute to the image folder, file with title from "thePhoto" object "photos" field and enable download. -->
                        <a class="dwhite" :href="'/storage/galleries/' + thePhoto.photos" download>Изтегли <i class="fas fa-file-download"></i></a>
                    </div>
                    <div class="closeButton"><!-- Class "closeButton" added for styling.  -->
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a> wich calls "singlePhotoModal" method. -->
                        <a href="" @click.prevent="singlePhotoModal()"><i class="fas fa-times dwhite fa-3x"></i></a>
                    </div>
                    <div class="rightButton"><!-- Class "rightButton" added for styling.  -->
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a> wich calls "nextPhoto" method. -->
                        <a href="" @click.prevent="nextPhoto()"><i class="fas fa-chevron-right dwhite fa-3x"></i></a>
                    </div>
                    <div class="leftButton"><!-- Class "leftButton" added for styling.  -->
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a> wich calls "prevPhoto" method. -->
                        <a href="" @click.prevent="prevPhoto()"><i class="fas fa-chevron-left dwhite fa-3x"></i></a>
                    </div>
            </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
            <div class="modal fade" id="photosModal" tabindex="-1" role="dialog" aria-labelledby="photosModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div v-if="adminMode" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="photosModalLabel">Добавяне на галерия</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
<!-- The following <form> when submitted calls "createPhoto" method.-->
                        <form @submit.prevent="createPhoto()" id="photosForm" enctype="multipart/form-data">
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
                                <button type="submit" class="btn btn-success">Добави</button>
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
                adminMode : false, // swich to show administrator content
                photoMethod : false, // swich to show a single photo container
                gallery: null, // holds the records from the DB
                displayedGallery: null, //holds the records to display
                photoIndex : null, //holds the index of a single record
                thePhoto : null, //holds the signle record
                allPhotos : null, // holds the number of all the photos get from the DB
                loadedPhoto : 16, // the numer of photos to display
                form : new Form ({ // instantiate a new Form object
                    title : null, // title of/for the DB record
                    photos : null, // image of/for the DB record
                }),
            }
        },
        methods: {
            showModal(){ // show the form modal
                $("#photosModal").modal("show");
            },
            changeAdminMode() { //check is the user administrator
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getPhotos() { //request to the backend to get the records
                axios.get("/api/gallery/"+ this.$route.params.id)
                .then(({ data }) => {
                    this.gallery = data
                    this.allPhotos = data.length
                    this.displayGallery()
                })
                .catch(() => {

                });
            },
            createPhoto(){ //request to the backend to create a new record
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
            deletePhoto (id) { // request to the backend to delete a record
                if(this.$gate.isAdmin()) {
                    axios.delete('/api/gallery/' + id)
                    .then(() => {
                        this.getPhotos();
                    })
                    .catch(() => {

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
            displayGallery() {// slice the gallery array to display the desired results
                this.displayedGallery = this.gallery.slice(0 , this.loadedPhoto)
            },
            singlePhotoModal(index) { // set the parameters to display a single record
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
            nextPhoto() { // set the parameters to display next single record
                if(this.photoIndex < this.gallery.length - 1) {
                    this.photoIndex += 1
                    this.thePhoto = this.gallery[this.photoIndex]
                } else {
                    this.photoIndex = 0
                    this.thePhoto = this.gallery[this.photoIndex]
                }
            },
            prevPhoto() { // set the parameters to display previous single record
                if(this.photoIndex >= 1) {
                    this.photoIndex -= 1
                    this.thePhoto = this.gallery[this.photoIndex]
                } else {
                    this.photoIndex = this.gallery.length - 1
                    this.thePhoto = this.gallery[this.photoIndex]
                }
            },
            loadMorePhoto() { // set the number of recors to display
                this.loadedPhoto +=16
                this.displayGallery()
            }
        },
        mounted() {
            this.getPhotos()
            this.changeAdminMode()
        }
    }
</script>
