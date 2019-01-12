<template>
    <div class="row flex-xl-nowrap">
        <div class="schedules col-12">
            <a href="#" v-if="adminMode" @click.prevent="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
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
                    <div v-if="adminMode" class="p-1 w-100">
                    <hr class="mb-0 mt-0">
                        <a class="float-left" href="#" @click.prevent="editSchedule(schedule)">
                        <i class="fas fa-edit yellow"></i>&#160;</a>
                        <a class="float-right" href="#" @click.prevent="deleteSchedule(schedule.id)">
                        <i class="fas fa-trash red"></i>&#160;</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div v-if="adminMode" class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="!editMode" class="modal-title" id="scheduleModalLabel">Добавяне на график</h5>
                        <h5 v-if="editMode" class="modal-title" id="scheduleModalLabel">Промяна на график {{ this.form.id }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
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
                adminMode : false,
                editMode : false,
                schedules : null,
                form : new Form({
                    id: null,
                    title : null,
                    comment : null,
                    mon : null,
                    tue : null,
                    wen : null,
                    thr : null,
                    fri : null,
                    sat : null,
                    sun : null,
                }),
            }
        },
        methods: {
            clearForm() {
                if (this.editMode) {
                    this.form.originalData.id = this.form.id
                    this.form.reset();
                } else if (!this.editMode){
                    this.form.originalData.id = null
                    this.form.reset();
                }
            },
            changeAdminMode() {
                if(this.$gate.isAdmin()) {
                    this.adminMode = true
                } else {
                    this.adminMode = false
                }
            },
            getSchedules() {
                axios.get("/api/schedules")
                .then(( data ) => {this.schedules = data.data})
                .catch(() => {

                });
            },
            showModal(){
                this.editMode = false
                $("#scheduleModal").modal("show");
            },
            createSchedule(){
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
            editSchedule(data){
                this.editMode = true;
                $("#scheduleModal").modal("show");
                this.form.fill( data );
            },
            updateSchedule() {
                this.form.patch('/api/schedules/' + this.form.id)
                .then(() => {
                    $('#scheduleModal').modal('hide');
                    this.getSchedules();
                })
                .catch(() => {
                })
            },
            deleteSchedule(id){
               axios.delete('/api/schedules/' + id)
               .then(() => {
                   this.getSchedules();
                })
                .catch(() => {

                })
            },

        },
        mounted() {
            this.getSchedules()
            this.changeAdminMode()
        }
    }
</script>
