<!--Displays the contents of each galleries. When the logged in user is administrator the content editing tools are shown. -->
<template>
    <div class="row flex-xl-nowrap">
        <div class="col-12 pl-0 pr-0">
<!--The following <div> is shown only if "adminMode" parameter is set to true.
    The click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
            <div v-if="adminMode" class="col-12">
                <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
            </div>
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
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "galleries" parameter.  -->
            <div v-for="gallery in galleries" :key="gallery.id">
<!-- Vue router link to gallery component -->
                <router-link :to="'/Галерии/'+ gallery.category + '/Галерия/' + gallery.title + '/Id/' + gallery.id">
                    <div class="gallery float-left" :style="{ 'background-image': 'url(/storage/images/' + gallery.photos + ')' }">
                    <p class="gallery-title dwhite w-100 text-center">Галерия - {{ gallery.title }}</p>
                    <p class="gallery-date dwhite mb-0">Дата: {{ gallery.date }}</p>
<!-- The following <div> is shown if adminMode parameter is false (the user is not administrator)-->
                    <div v-if="adminMode">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
and wich calls "editGalleries" method passing the gallery object to it. -->
                        <a href="" @click.prevent="editGalleries(gallery)"><i class="fas fa-edit yellow fa-2x"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
and wich calls "deleteGalleries" method passing the gallery id to it. -->
                        <a href="" @click.prevent="deleteGalleries(gallery.id)"><i class="fas fa-trash red fa-2x"></i>&#160;</a>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                        <a :href="'/Снимки/' + gallery.photos"><i class="fas fa-cut blue fa-2x"></i>&#160;</a>
                    </div>
                </div>
                </router-link>
            </div>
        </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
        <div class="modal fade" id="galleriesModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на галерия</h5>
                        <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на галерия {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
<!-- The following <form> when submitted calls "updateGalleries" or "createGalleries" method based of the editMode parameter condition.-->
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
<!-- The following <div> holds buttons based on editMode parameter condition and button to clear the form with that call clearForm method -->
                        <div class="modal-footer">
                            <button type="button" @click="clearForm()" class="btn btn-danger">Изчисти</button>
                            <button type="submit" v-show="!editMode" class="btn btn-success">Добави</button>
                            <button type="submit" v-show="editMode" class="btn btn-primary">Промени</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                        </div>
                    </form>
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
                galleries : null, // holds the records from the DB
                editMode : false, // swich the edit mode
                page: 1,
                pages: null,
                form : new Form ({ //instantiate a new Form object
                    id : null, // id of DB record
                    title : null, // title of DB record
                    date : null, // date of DB record
                    photos : null, // photos of DB record
                    category: this.$route.params.type, // category of DB record will be equal to the route parameter type
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
                $("#galleriesModal").modal("show");
            },
            changeAdminMode() { //check is the user administrator
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
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
            getPage() { //based on user nextPage, prevPage methods or user input calls getGalleries method to send request to backend with the page number
                this.$route.params.page = this.page
                 if (history.pushState) {
                        let url = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        let slicedUrl = url.slice(0, url.lastIndexOf('/'))
                        let newUrl = slicedUrl + '/' + this.$route.params.page
                        window.history.pushState({path:newUrl},'',newUrl);
                        this.getGalleries()
                    }
            },
            getGalleries() { //request to the backend to get the records
                axios.get("/api/galleries/"+ this.$route.params.type +"?page=" + this.$route.params.page)
                .then(({ data }) => {
                    this.galleries = data.data
                    this.pages = data.last_page
                })
                .catch(() => {

                });
            },
            createGalleries(){ //request to the backend to create a new record
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
            editGalleries(data){ // display the form to edit DB records
                this.editMode = true;
                $("#galleriesModal").modal("show");
                this.form.fill( data );
            },
            updateGalleries() { // request to the backend to edit a specific record
                this.form.patch('/api/galleries/' + this.form.id)
                .then(() => {
                    $('#galleriesModal').modal('hide');
                    this.getGalleries();
                })
                .catch(() => {
                })
            },
            deleteGalleries (id) { // request to the backend to delete a record
            if(this.$gate.isAdmin()) {
                    axios.delete('/api/galleries/' + id)
                    .then(() => {
                        this.getGalleries();
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
        },
        beforeRouteUpdate (to, from, next) { //change the form category when the vue router update
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
