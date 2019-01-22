<template>
    <div class="row flex-xl-nowrap">

        <div class="col-12 col-md-2 pt-2 pr-0">
            <div class="bg-light text-center user-menu">
                <button class="mt-2  btn btn-primary" v-if="this.$gate.isAdmin() && showUsersTable" @click="showUsersTable = false">виж <i class="fas fa-users"></i></button>
                <button class="mt-2  btn btn-primary" v-if="this.$gate.isAdmin() && !showUsersTable" @click="getUsers()">скрий <i class="fas fa-users"></i></button>
                <p class="mt-2 pt-2"> Здравей {{user.firstName}}!</p>
                <img class="rounded-circle mt-2 img-fluid" :src="'/storage/images/' + user.photos" :alt="user.firstName + ' ' + user.lastName">
                <ul class="pl-2 pr-2 pt-2 pb-2">
                    <li class="mt-2 pt-1 pb-1 bg-dark text-white rounded" @click="uploadImg()">
                        <i class="fas fa-user-edit"></i> снимка</li>
                    <li class="mt-2 pt-1 pb-1 bg-dark text-white rounded" @click="changePass()">
                        <i class="fas fa-edit"></i> парола</li>
                    <li class="mt-5 pt-1 pb-1 bg-danger text-white rounded" @click="deleteUser()">
                        <i class="fas fa-trash"></i> изтрий профила</li>
                </ul>
                <input type="file" style="display:none" id="imgupload" @change="onFileSelect" name="photos" class="form-control">
            </div>
        </div>

        <div class="col-12 col-md-10">
            <table v-if="this.$gate.isAdmin() && showUsersTable" class="table table-dark">
                  <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Име</th>
                        <th scope="col">Фамиля</th>
                        <th scope="col">Ел.поща</th>
                        <th scope="col">Роля</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="userList in this.users" :key="userList.id">
                        <td>{{ userList.id }}</td>
                        <td>{{ userList.firstName }}</td>
                        <td>{{ userList.lastName }}</td>
                        <td>{{ userList.email }}</td>
                        <td v-if="user != userList">
                            <select @change="changeUserRole(userList)" name="user-role" :id="'user-role' + userList.id">
                                <option :value="user.role">{{ userList.role }}</option>
                                <option v-if="userList.role != 'admin'" value="admin">admin</option>
                                <option v-if="userList.role != 'modelator'" value="modelator">modelator</option>
                                <option v-if="userList.role != 'user'" value="user">user</option>
                            </select>
                        </td>
                        <td v-if="user == userList">{{ userList.role }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="col-12 col-md-6 float-left">
                <div class="bg-dark rounded-top mt-2"><h3 class="dwhite text-center">Вашите теми</h3></div>
                <div></div>
            </div>
            <div class="col-12 col-md-6 float-left">
                <div class="bg-dark rounded-top mt-2"><h3 class="dwhite text-center">Вашите коментари</h3></div>
                <div></div>
            </div>
        </div>

        <div class="modal fade" id="userPhotoEditor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Смяна на профилна снимка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pl-0 pr-0 pb-0 pt-0">
                    <span class="col-12 dwhite pl-4 bg-secondary">размер на кръгът :
                        <input class="col-8" type="range" min="10" max="100"
                        @mousemove="setCircleSize()" v-model="circleSize" id="myRange">
                        {{this.circleSize}}%</span><br>
                    <div class="resizer-container col-12 pl-0 pr-0" id="user-photo-resizer-container"
                    :style="'height:' + this.photoHeight + 'px; width:' + this.photoWidth + 'px;' +
                    'margin-left:' + this.parentMargin + 'px; margin-right:' + this.parentMargin + 'px;'">
                        <img id="user-photo-update" class="img-fluid" :src="this.photos" alt="your image"/>
                        <div id="user-photo-croper" :style="'height:' + this.circleDiameter + 'px; width:' + this.circleDiameter + 'px'"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="changePhoto" class="btn btn-primary">Запази</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Откажи</button>
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
                users : null,
                showUsersTable : false,
                page : 1,
                pages : null,
                user : this.$gate.isUser(),
                role : null,
                photos : null,
                photoHeight : null,
                photoWidth : null,
                parentWidth : null,
                parentMargin : null,
                circleDiameter : 250,
                circleSize : 50,
            }
        },
        methods: {
            getUsers() {
                this.showUsersTable = true
                if(this.$gate.isAdmin()) {
                    axios.get("/api/user?page=" + this.page)
                    .then(({ data }) => {
                        this.users = data.data
                        this.pages = data.last_page
                    })
                    .catch(() => {

                    });
                }
            },
            changeUserRole (user) {
                let newRole = $("#user-role" + user.id).children("option:selected").val();
                if(this.$gate.isAdmin()) {
                    axios.patch("/api/user/"+ user.id, {
                            changeRole : newRole
                    })
                    .then(() => {
                        location.reload()
                    })
                    .catch(() => {

                    });
                }
            },
            uploadImg() {
                $("#imgupload").trigger('click');
            },
            onFileSelect(event) { //instantiate new FileReader object for the selected file
                let file = event.target.files[0];
                this.photos = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                   this.photos = reader.result
                }
                reader.readAsDataURL(file)
                $("#userPhotoEditor").modal("show")
                setTimeout(this.getPhotoSize, 300)
            },
            changePhoto () {
                let croper = document.getElementById("user-photo-croper"); //get croper element by id
                axios({
                    method : 'patch',
                    url :'/api/user/'+ user.id,
                    data :{
                        method : "cropPhoto",
                        photos : this.photos,
                        width : this.photoWidth,
                        height : this.photoHeight,
                        croperTop : parseInt(croper.style.top),
                        croperLeft : parseInt(croper.style.left),
                        croperWidth : parseInt(croper.style.width),
                        croperHeight : parseInt(croper.style.height)
                    }})
                    .then(() => {
                        location.reload();
                    }).catch(() =>{

                    })
            },
            changePass() {
                swal({
                    title: 'Смяна на парола.',
                    text: 'При натискане на бутона "Изпрати" ще ви изпратим имейл от където може да смените вашата парола.',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Откажи!',
                    confirmButtonText: 'Изпрати!'
                    }).then((result) => {
                        if (result.value) {
                            axios.post('/password/email', {
                                'email' : this.user.email
                            })
                            .then(() => {
                                $('#userModal').modal('hide');
                                this.form.reset();
                                location.reload();
                            }).catch(() =>{

                            })
                        }
                    })
            },
            deleteUser() {
                swal({
                    title: 'Изтриване на профила!',
                    text: `Сигорни ли сте? При потвръждаване на това съобщение ще изтриете профила си и всички данни свързани с него`,
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Не, недей!',
                    confirmButtonText: 'Да, изтрий го!'
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('api/user/' + this.user.id, {

                            })
                            .then(() => {

                            }).catch(() =>{

                            })
                        }
                    })
            },
            setCircleSize() {
                this.circleDiameter = parseFloat(this.circleSize) * 5
            },
            getPhotoSize() {
                this.photoHeight = $("#user-photo-update").height()
                this.photoWidth = $("#user-photo-update").width()
                this.parentWidth = $("#user-photo-update").parent().width()
                this.parentMargin = (this.parentWidth - this.photoWidth) / 2
                if(this.parentMargin == 0) {
                    this.parentMargin = null
                }
                let drager = new DragElement()
                drager.dragElement(document.getElementById("user-photo-croper"))
            },
        },
        mounted() {
            this.getUsers()
        }
    }
</script>
