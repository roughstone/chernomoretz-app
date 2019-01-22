<template>
   <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" v-show="authenticateMode" @click="regUserMode()"><i class="fas fa-sign-in-alt green"></i> Вход&#160;</a>
                        <a href="#" v-show="!authenticateMode" @click="logUserMode()"><i class="fas fa-user-plus"></i> Регистрация&#160;</a>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="!authenticateMode ? logUserIn() : createUser()">
                        <div class="modal-body">
                            <div class="form-group" v-if="authenticateMode">
                                <input v-model="form.firstName" type="text" name="firstName"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('firstName') }" placeholder="Име" required>
                                <has-error :form="form" field="firstName"></has-error>
                            </div>
                            <div class="form-group" v-if="authenticateMode">
                                <input v-model="form.lastName" type="text" name="lastName"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('lastName') }" placeholder="Фамилия" required>
                                <has-error :form="form" field="lastName"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="text" name="email"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Електрнна поща" required>
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-if="!forgotPassword" v-model="form.password" type="password" name="password" id="password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Парола">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            <div class="form-group" v-if="authenticateMode">
                                <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password-confirm"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" placeholder="Повторете паролата">
                                <has-error :form="form" field="password_confirmation"></has-error>
                            </div>
                            <div v-if="!authenticateMode && !forgotPassword">
                            <input v-model="form.remember" type="checkbox" name="remember" id="remember"> Запомни ме!
                            </div>
                            <a v-if="!authenticateMode && !forgotPassword" href="" @click.prevent="forgotPassword = true">Забравихте паролата си?</a>
                            <p v-if="forgotPassword">Въведете вашата електронна поща и ще ви изпратим емейл за възстановяване на паролата ви.</p>
                        </div>
                        <div class="modal-footer">
                            <button v-if="forgotPassword" type="button" @click="passwordReset()" class="btn btn-success">Въведи</button>
                            <button v-if="!authenticateMode && !forgotPassword" type="submit" class="btn btn-success">Влез</button>
                            <button v-if="authenticateMode" type="submit" class="btn btn-success">Регистрация</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
         data() {
            return {
                authenticateMode : false,
                forgotPassword : false,
                form: new Form({
                    id : '',
                    remember : false,
                    firstName : '',
                    lastName : '',
                    email : '',
                    password : '',
                    password_confirmation : ''
                })
            }
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        methods : {
            createUser () {
                this.form.post('/register')
                .then(() => {
                    $('#userModal').modal('hide');
                    this.form.reset();
                })
                .catch(() => {
                })
            },
            logUserMode () {
                this.authenticateMode = true
            },
            regUserMode () {
                this.authenticateMode = false
            },
            logUserIn () {
                this.form.post('/login')
                .then(() => {
                    $('#userModal').modal('hide');
                    this.form.reset();
                    location.reload();
                }).catch(() =>{

                })
            },
            passwordReset() {
                axios.post('/password/email', {
                    'email' : this.form.email
                })
                .then(() => {
                    $('#userModal').modal('hide');
                    this.form.reset();
                    location.reload();
                }).catch(() =>{

                })
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
