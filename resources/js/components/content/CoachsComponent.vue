<!--Displays the contents of each coachs. When the logged in user is administrator the content editing tools are shown. -->
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
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "coachs" parameter.  -->
        <div v-for="coach in coachs" :key="coach.id" :class="'col-12 col-md-4 float-left coachs'">
            <div class="coach">
                <a href="#" @click.prevent="editCoach(coach)" class="dwhite">
                    <img :class="'img-fluid float-left'" :src="'/storage/images/' + coach.photos" :alt="coach.title">
                    <div class="col-6 pr-0 float-left mt-3">
                        <p class="text-right h4">Име:&#160;</p>
                        <p class="text-right h4">Презиме:&#160;</p>
                        <p class="text-right h4">Години:&#160;</p>
                    </div>
                    <div class="col-6 pl-0 float-left mt-3">
                        <p class="text-left h4">{{ coach.firstName }}</p>
                        <p class="text-left h4">{{ coach.lastName }}</p>
                        <p class="text-left h4">{{ coach.birthday | getAge }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-center h6 mb-0 underline">специалист по:</p>
                        <p class="text-center h4">{{ coach.position }}</p>
                    </div>
                </a>
<!-- The following <div> is shown if adminMode parameter is true (the user is administrator)-->
                <div v-if="adminMode" class="p-1 d-inline-block w-100">
                    <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
and wich calls "editCoach" method passing the coach object to it. -->
                    <a href="" @click.prevent="editCoach(coach)"><i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a>
and wich calls "deleteCoach" method passing the coach id to it. -->
                    <a href="" @click.prevent="deleteCoach(coach.id)"><i class="fas fa-trash red"></i>&#160;</a>
<!--The following <a> has binded href atribute to imagerComponent and passing the photo title to it.  -->
                    <a :href="'/Снимки/' + coach.photos+'/Категория/coachs'"><i class="fas fa-cut blue"></i>&#160;</a>
                </div>
            </div>
        </div>
   </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
   <div class="modal fade" id="coachsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
<!--The following <div> is shown if adminMode parameter is true (the user is administrator)-->
            <div v-if="adminMode" class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на треньор</h5>
                    <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на треньор {{ this.form.id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
<!-- The following <form> when submitted calls "updateCoach" or "createCoach" method based of the editMode parameter condition.-->
                <form @submit.prevent="editMode ? updateCoach() : createCoach()" id="newsForm" enctype="multipart/form-data">
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
                            <label>Треньор по:</label>
                            <input v-model="form.position" type="text" name="position"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('position') }" placeholder="Позиция" required>
                            <has-error :form="form" field="position"></has-error>
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
                    <p class="dwhite"><img :class="'img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/images/' + this.form.photos" :alt="this.form.firstName +' '+ this.form.lastName">{{ this.form.description }}</p>
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
                coachs : null, // holds the records from the DB
                form : new Form ({ //instantiate a new Form object
                    id : null, // id of DB record
                    firstName : null, // firstName of DB record
                    lastName : null, // lastName of DB record
                    birthday : null, // birthday of DB record
                    photos : null, // photos of DB record
                    position: null, // position of DB record
                    description : null, // description of DB record
                })
            }
        },
        methods: {
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
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#coachsModal").modal("show")
            },
            getCoachs() { //request to the backend to get the records
                this.$loadStart()
                axios.get("/api/coachs")
                .then(({ data }) => {
                    this.coachs = data.data
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
            createCoach(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.$loadStart()
                    this.form.post('/api/coachs')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов треньор!'})
                        $('#coachsModal').modal('hide')
                        this.getCoachs()
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
            editCoach(data){ // display the form to edit DB records
                this.editMode = true
                $("#coachsModal").modal("show")
                this.form.fill( data )
            },
            updateCoach() { // request to the backend to edit a specific record
                if(this.$gate.isAdmin()) {
                    this.$loadStart()
                    this.form.patch('/api/coachs/' + this.form.id)
                    .then(() => {
                        $('#coachsModal').modal('hide')
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getCoachs()
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
            deleteCoach (id) { // request to the backend to delete a record
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
                            axios.delete('/api/coachs/' + id)
                            .then(() => {
                                this.getCoachs()
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
            changeAdminMode() { //check is the user administrator
                    if(this.$gate.isAdmin()) {
                        this.adminMode = true
                    } else {
                        this.adminMode = false
                    }
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
            this.changeAdminMode()
            this.getCoachs()
        }
    }
</script>
