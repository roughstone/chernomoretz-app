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
                                <input v-model="form.password" type="password" name="password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Парола">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!--<button type="submit" class="btn btn-primary">Промени</button>-->
                            <button type="submit" class="btn btn-success">Добави</button>
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
                authenticateMode: false,

                form: new Form({
                    id : '',
                    firstName : '',
                    lastName : '',
                    email : '',
                    password : ''
                })
            }
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        methods : {
            createUser () {
                // Submit the form via a POST request
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
            }


        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
