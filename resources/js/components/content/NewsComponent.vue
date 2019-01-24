<!--Displays the contents of each news. When the logged in user is administrator the content editing tools are shown. -->
<template>
<div class="row flex-xl-nowrap">
   <div class="col-12 col-lg-9 col-md-8">
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
                <a :href="'./'+ ((this.page > 1) ? (this.page - 1) : this.pages)" class="page-link"><i class="fas fa-chevron-left"></i></a>
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
                <a :href="'./'+ ((this.page < this.pages) ? (this.page + 1) : 1)" class="page-link"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "news" parameter.  -->
        <div v-for="news in news" :key="news.id">
            <div class="mt-3 news">
                <a href="" @click.prevent="editNews(news)">
                    <h3 class=" pl-3 dwhite">{{ news.title }}</h3>
                    <p class="mb-0 float-left w-100 dwhite"><img :class="'col-6 col-md-3 p-1 float-left'" :src="'/storage/images/' + news.photos" :alt="news.title">{{ news.description }}</p>
<!-- The following <div> is shown if adminMode parameter is false (the user is not administrator)-->
                    <div v-if="!adminMode" class="p-1 d-inline-block w-100">
                        <hr class="mb-0 mt-0">
                        <p  class="mb-0 mt-0 ml-1 float-left dwhite">Виж още</p>
                        <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ news.date }}</h5>
                    </div>
                </a>
<!-- The following <div> is shown if adminMode parameter is true (the user is administrator)-->
                <div v-if="adminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "editNews" method passing the news object to it. -->
                    <a class="float-left" href="" @click.prevent="editNews(news)">
                    <i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
    and wich calls "deleteNews" method passing the news id to it. -->
                    <a class="float-left" href="" @click.prevent="deleteNews(news.id)">
                    <i class="fas fa-trash red"></i>&#160;</a>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                    <a class="float-left" :href="'/Снимки/' + news.photos"><i class="fas fa-cut blue"></i>&#160;</a>
                    <h5 class="mb-0 mt-0 mr-1 float-right dwhite">Дата: {{ news.date }}</h5>
                </div>
            </div>
        </div>
    </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
     <div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
<!--The following <div> is shown if adminMode parameter is true (the user is administrator)-->
            <div v-if="adminMode" class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на новина</h5>
                    <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на новина {{ this.form.id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
<!-- The following <form> when submitted calls "updateNews" or "createNews" method based of the editMode parameter condition.-->
                <form @submit.prevent="editMode ? updateNews() : createNews()" id="newsForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Заглавие:</label>
                            <input v-model="form.title" type="text" name="title"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                            <has-error :form="form" field="title"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Дата на новината:</label>
                            <input v-model="form.date" type="date" name="date"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('date') }" placeholder="Дата" required>
                            <has-error :form="form" field="date"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Снимка"</label>
                            <input type="file" @change="onFileSelect" name="photos" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
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
                    <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/images/' + this.form.photos" :alt="this.form.title">
                    {{ this.form.description }}</p>
                </div>
            </div>
        </div>
    </div>
<!-- Display the globaly registrated vuejs announcement component. -->
    <router-view></router-view>
</div>
</template>

<script>
    export default {
        data() {
            return {
                adminMode : false, // swich to show administrator content
                editMode : false, // swich the edit mode
                news : null, // holds the records from the DB
                page: 1, //current page
                pages: null, //numer of pages
                form : new Form({ //instantiate a new Form object
                    id: null, // id of DB record
                    title : null, // title of DB record
                    photos : null, // photos of DB record
                    description : null, // description of DB record
                    date : null, // date of DB record
                    category: 'news' // category of DB record set to 'news' because the DB holds 'announcement' category too.
                }),
            }
        },
        methods :{
             clearForm() { // clear the form based on editMode parameter condition keep data
                if (this.editMode) {
                    this.form.originalData.id = this.form.id
                    this.form.originalData.photos = this.form.photos
                    this.form.reset()
                } else if (!this.editMode){
                    this.form.originalData.id = null
                    this.form.originalData.photos = null
                    this.form.reset()
                }
            },
            changeAdminMode() { //check is the user administrator
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getPage() { //based on user nextPage, prevPage methods or user input calls getCompetitors method to send request to backend with the page number
                if(this.page != "" && !isNaN(this.page)) {
                    this.$route.params.page = this.page
                    let url = window.location.protocol + "//" + window.location.host + window.location.pathname
                    let slicedUrl = url.slice(0, url.lastIndexOf('/'))
                    let newUrl = slicedUrl + '/' + this.$route.params.page
                    window.history.pushState({path:newUrl},'',newUrl)
                    setTimeout(() => {
                    this.getNews()},200)
                }
            },
            getNews() { //request to the backend to get the records
                this.$loadStart()
                this.page = parseInt(this.$route.params.page)
                axios.get("/api/moreNews?page="+ this.$route.params.page)
                .then(({ data }) => {
                    this.news = data.data
                    this.pages = data.last_page
                    this.$loadEnd(500)
                })
                .catch(() => {
                    swal({
                        type: 'error',
                        title: 'Възникна грешка',
                        showConfirmButton: false,
                        timer: 2000,
                        reload: setTimeout(() => {
                            location.reload()
                        }, 2000)
                    })
                })
            },
            createNews(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.$loadStart()
                    this.form.post('/api/news')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте ново обявление!'})
                        $('#newsModal').modal('hide')
                        this.getNews()
                        this.form.reset()
                        this.$loadEnd(500)
                    })
                    .catch(() => {
                        swal({
                            type: 'error',
                            title: 'Възникна грешка',
                            showConfirmButton: false,
                            timer: 2000,
                            reload: setTimeout(() => {
                                location.reload()
                            }, 2000)
                        })
                    })
                }
            },
            editNews(data){ // display the form to edit DB records
                this.editMode = true
                $("#newsModal").modal("show")
                this.form.fill( data )
            },
            updateNews() { // request to the backend to edit a specific record
                if(this.$gate.isAdmin()) {
                    /* $("#loader").fadeIn(0) */
                    this.$loadStart()
                    this.form.patch('/api/news/' + this.form.id)
                    .then(() => {
                        $('#newsModal').modal('hide')
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getNews()
                        this.$loadEnd(500)
                    })
                    .catch(() => {
                        swal({
                            type: 'error',
                            title: 'Възникна грешка',
                            showConfirmButton: false,
                            timer: 2000,
                            reload: setTimeout(() => {
                                location.reload()
                            }, 2000)
                        })
                    })
                }
            },
            deleteNews(id){ // request to the backend to delete a record
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
                            this.$loadStart()
                            axios.delete('/api/news/' + id)
                            .then(() => {
                                this.getNews()
                                this.$loadEnd(500)
                            })
                            .catch(() => {
                                swal({
                                    type: 'error',
                                    title: 'Възникна грешка',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    reload: setTimeout(() => {
                                        location.reload()
                                    }, 2000)
                                })
                            })
                        }
                    })
                }
            },
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#newsModal").modal("show")
            },
            onFileSelect(event) { //instantiate new FileReader object for the selected file
                let file = event.target.files[0]
                this.form.photos = event.target.files[0]
                let reader = new FileReader()
                reader.onloadend = () => {
                   this.form.photos = reader.result
                }
                reader.readAsDataURL(file)
            },
        },
        mounted() {
            this.getNews()
            this.changeAdminMode()
        }
    }
</script>
