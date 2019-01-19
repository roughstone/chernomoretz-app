<!--Displays the contents schedules. When the logged in user is administrator the content editing tools are shown. -->
<template>
    <div class="row flex-xl-nowrap">
        <div class="schedules col-12">
<!--The following <a> is shown only if "adminMode" parameter is set to true.
    The click event that open the form to insert new records to the DB.
    On the click event the prevent method was called to prevent the default behavior of the <a>
    The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
            <a href="" v-if="adminMode" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
<!--The following <div> is multiplied by Vuejs v-for directive for each record form "schedules" parameter. -->
            <div v-for="schedule in schedules" :key="schedule.id">
                <div class="schedule">
                    <div class="schTitle h3"><p class="dwhite">{{ schedule.title }}</p></div>
                    <div class="schDay float-left"><p>Понеделник:</p><p>{{ schedule.mon }}</p></div>
                    <div class="schDay float-left"><p>Вторник:</p><p>{{ schedule.tue }}</p></div>
                    <div class="schDay float-left"><p>Сряда:</p><p>{{ schedule.wen }}</p></div>
                    <div class="schDay float-left"><p>Четвъртък:</p><p>{{ schedule.thr }}</p></div>
                    <div class="schDay float-left"><p>Петък:</p><p>{{ schedule.fri }}</p></div>
                    <div class="schDay float-left"><p>Събота:</p><p>{{ schedule.sat }}</p></div>
                    <div class="schDay float-left"><p>Неделя:</p><p>{{ schedule.sun }}</p></div>
                    <div class="schComment w-100 d-inline-block"><p class="dwhite">{{ schedule.comment }}</p></div>
<!--The following <div> checks is the user an administrator if it is, the <div> is displayed on the document. -->
                    <div v-if="adminMode" class="p-1 w-100">
                    <hr class="mb-0 mt-0">
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a> wich calls "editSchedule" method
    and pass it the schedule object. The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
                        <a class="float-left" href="" @click.prevent="editSchedule(schedule)">
                        <i class="fas fa-edit yellow"></i>&#160;</a>
<!--The following <a> has click event with .prevent called to prevent the default behavior of the <a> wich calls "deleteSchedule" method
    and pass it the schedule id. The href attribute is empty to keep the pointer:cursor behavior of the <a> -->
                        <a class="float-right" href="" @click.prevent="deleteSchedule(schedule.id)">
                        <i class="fas fa-trash red"></i>&#160;</a>
                    </div>
                </div>
            </div>
        </div>
<!--The following <div> is created based on "bootstrap" and "vForm" packages -->
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
<!--The following <div> is shown only if "adminMode" parameter is set to true. Title is chosen based on editMode parameter condition-->
                <div v-if="adminMode" class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="scheduleModalLabel">Добавяне на график</h5>
                        <h5 v-if="editMode" class="modal-title" id="scheduleModalLabel">Промяна на график {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
<!-- The following <form> when submitted calls "updateSchedule" or "createSchedule" method based of the editMode parameter condition.-->
                    <form @submit.prevent="editMode ? updateSchedule() : createSchedule()" id="scheduleForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Заглавие:</label>
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Коментар:</label>
                                <input v-model="form.comment" type="text" name="comment"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('comment') }" placeholder="Коментар">
                                <has-error :form="form" field="comment"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Понеделник:</label>
                                <input v-model="form.mon" type="text" name="mon"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('mon') }" placeholder="Понеделник">
                                <has-error :form="form" field="mon"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Вторник:</label>
                                <input v-model="form.tue" type="text" name="tue"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('tue') }" placeholder="Вторник">
                                <has-error :form="form" field="tue"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Сряда:</label>
                                <input v-model="form.wen" type="text" name="wen"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('wen') }" placeholder="Сряда">
                                <has-error :form="form" field="wen"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Четвъртък:</label>
                                <input v-model="form.thr" type="text" name="thr"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('thr') }" placeholder="Четвъртък">
                                <has-error :form="form" field="thr"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Петък:</label>
                                <input v-model="form.fri" type="text" name="fri"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('fri') }" placeholder="Петък">
                                <has-error :form="form" field="fri"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Събота:</label>
                                <input v-model="form.sat" type="text" name="sat"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('sat') }" placeholder="Събота">
                                <has-error :form="form" field="sat"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Неделя:</label>
                                <input v-model="form.sun" type="text" name="sun"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('sun') }" placeholder="Неделя">
                                <has-error :form="form" field="sun"></has-error>
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
                editMode : false, // swich the edit mode
                schedules : null, // holds the records from the DB
                form : new Form({ //instantiate a new Form object
                    id: null, // id of DB record
                    title : null, // title of DB record
                    comment : null, // comment of DB record
                    mon : null, // data of DB record
                    tue : null, // data of DB record
                    wen : null, // data of DB record
                    thr : null, // data of DB record
                    fri : null, // data of DB record
                    sat : null, // data of DB record
                    sun : null, // data of DB record
                }),
            }
        },
        methods: {
            clearForm() { // clear the form based on editMode parameter condition keep data
                if (this.editMode) {
                    this.form.originalData.id = this.form.id
                    this.form.reset();
                } else if (!this.editMode){
                    this.form.originalData.id = null
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
            getSchedules() { //request to the backend to get the records
                axios.get("/api/schedules")
                .then(( data ) => {this.schedules = data.data})
                .catch(() => {

                });
            },
            showModal(){ // display the form to insert DB records
                this.editMode = false
                $("#scheduleModal").modal("show");
            },
            createSchedule(){ //request to the backend to create a new record
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/schedules')
                    .then(() => {
                        toast({type: 'success', title: 'Успешно добавихте нов график!'});
                        $('#scheduleModal').modal('hide');
                        this.getSchedules();
                        this.form.reset();
                    })
                    .catch(() => {

                    })
                }
            },
            editSchedule(data){ // display the form to edit DB records
                this.editMode = true;
                $("#scheduleModal").modal("show");
                this.form.fill( data );
            },
            updateSchedule() { // request to the backend to update specific record
                if(this.$gate.isAdmin()) {
                    this.form.patch('/api/schedules/' + this.form.id)
                    .then(() => {
                        $('#scheduleModal').modal('hide');
                        toast({type: 'success', title: 'Промяната приложена успешно!'})
                        this.getSchedules();
                    })
                    .catch(() => {
                    })
                }
            },
            deleteSchedule(id){ // request to the backend to delete specific record
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
                            axios.delete('/api/schedules/' + id)
                            .then(() => {
                                this.getSchedules();
                                })
                            .catch(() => {

                            })
                        }
                    })
                }
            },

        },
        mounted() {
            this.getSchedules()
            this.changeAdminMode()
        }
    }
</script>
