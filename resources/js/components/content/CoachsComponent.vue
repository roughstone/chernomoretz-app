<template>
<div class="row flex-xl-nowrap">
   <div class="col-12">
        <div v-if="adminMode" class="col-12">
        <a href="" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        </div>
        <div v-for="coach in coachs" :key="coach.id" :class="'col-12 col-md-4 float-left coachs'">
            <div class="coach">
                <a href="#" @click.prevent="editCoach(coach)" class="dwhite">
                    <img :class="'img-fluid float-left'" :src="'/storage/coachs/' + coach.photos" :alt="coach.title">
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
                        <p class="text-center h6 mb-0 underline">треньор по</p>
                        <p class="text-center h4">{{ coach.position }}</p>
                    </div>
                    <div v-if="adminMode" class="p-1 d-inline-block w-100">
                        <hr class="mb-0 mt-0">
                        <a href="" @click.prevent="editCoach(coach)"><i class="fas fa-edit yellow"></i>&#160;</a>
                        <a href="" @click.prevent="deleteCoach(coach.id)"><i class="fas fa-trash red float-right"></i>&#160;</a>
                    </div>
                </a>
            </div>
        </div>
   </div>



   <div class="modal fade" id="coachsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div v-if="adminMode" class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!editMode" class="modal-title" id="newsModalLabel">Добавяне на треньор</h5>
                    <h5 v-if="editMode" class="modal-title" id="newsModalLabel">Промяна на треньор {{ this.form.id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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
                    <p class="dwhite"><img :class="'img-fluid img-fluid col-6 p-1 float-left mt-1'" :src="'/storage/coachs/' + this.form.photos" :alt="this.form.firstName +' '+ this.form.lastName">{{ this.form.description }}</p>
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
                coachs : null,
                form : new Form ({
                    id : null,
                    firstName : null,
                    lastName : null,
                    birthday : null,
                    photos : null,
                    position: null,
                    description : null,
                })
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
                $("#coachsModal").modal("show");
            },
            getCoachs() {
                axios.get("/api/coachs")
                .then(({ data }) => {this.coachs = data.data})
                .catch(() => {

                });
            },
            createCoach(){
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/coachs')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов треньор!'})
                        $('#coachsModal').modal('hide');
                        this.getCoachs()
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editCoach(data){
                this.editMode = true;
                $("#coachsModal").modal("show");
                this.form.fill( data );
            },
            updateCoach() {
                this.form.patch('/api/coachs/' + this.form.id)
                .then(() => {
                    $('#coachsModal').modal('hide');
                    this.getCoachs();
                })
                .catch(() => {
                })
            },
            deleteCoach (id) {
                axios.delete('/api/coachs/' + id)
                .then(() => {
                    this.getCoachs();
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
            this.getCoachs();
        }
    }
</script>
