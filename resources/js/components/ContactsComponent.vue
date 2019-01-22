<template>
<div class="row flex-xl-nowrap">
    <div class="col-12 col-md-9">
<div v-if="contactsForm" class="modal" id="contactsModal" tabindex="-1" role="dialog" aria-labelledby="contactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content for-user">
            <div class="modal-header mb-0 contactsList">
                <h5 class="modal-title dwhite w-100 text-center" id="contactsModalLabel">Контакти с нас!!!</h5>
                <button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form v-if="!contactsMode" @submit.prevent="sendQuestion()" id="newsForm" enctype="multipart/form-data">
                <div class="modal-body contactsList">
                    <div class="form-group">
                        <label class="dwhite">Имена:</label>
                        <input v-model="form.names" type="text" name="names"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('names') }" placeholder="Вашите именна" required>
                        <has-error :form="form" field="names"></has-error>
                    </div>
                    <div class="form-group">
                        <label class="dwhite">Електронна поща:</label>
                        <input v-model="form.email" type="text" name="email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Електронна поща" required>
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label class="dwhite">Вашето съобщение</label>
                        <textarea rows="4" v-model="form.text" type="text" name="text"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('text') }" placeholder="Текст към съобщението" required></textarea>
                        <has-error :form="form" field="text"></has-error>
                    </div>
                </div>
                <div class="modal-footer contactsList">
                    <button type="submit" class="btn btn-success">Изпрати</button>
                    <button type="button" @click="changeContacts()" class="btn btn-secondary">Затвори</button>
                </div>
            </form>
            <div v-if="contactsMode" class="contactsList">
                <div class="ml-4 mt-3 dwhite">Електронна поща: chernomoretz_gym@abv.bg</div>
                <div class="ml-4 mt-3 dwhite">Телефони за връзка: +359888337777, +359896787919 </div>
                <div class="ml-4 mt-3 dwhite">Адрес: Гр.Бургас , к-с.Лазур , СЗ"Лукойл Нефтохимик"</div>

                <a target="_blank" href="https://www.google.com/maps/place/%D0%A1%D0%BF%D0%BE%D1%80%D1%82%D0%BD%D0%B0+%D0%B7%D0%B0%D0%BB%D0%B0+%D0%9D%D0%B5%D1%84%D1%82%D0%BE%D1%85%D0%B8%D0%BC%D0%B8%D0%BA/@42.5115427,27.4678248,17z/data=!4m5!3m4!1s0x40a694846848bdbf:0xc44b47b0330a47d6!8m2!3d42.5122628!4d27.4684542">
                <div class="ml-4 mr-4 mb-3"><img class="img-fluid" src="/images/google_maps.jpg"></div>
                </a>
                <div class="text-center dwhite mb-3" @click="changeContacts()"><span>Изпрати съобщение</span></div>
            </div>
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
                contactsForm : null,
                updateForm : setInterval(this.getModal ,100),
                contactsMode : true,
                form : new Form({
                    names : null,
                    email : null,
                    text : null
                }
            )}
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        methods: {
            getModal() {
                this.contactsForm = this.$route.params.contactsForm
                if(this.contactsForm) {
                    $("#contactsModal").modal("show")
                }
            },
            closeModal() {
                this.contactsForm = false
                this.contactsMode = true
                this.$route.params.contactsForm = false
                this.form.reset();
            },
            changeContacts() {
                if(this.contactsMode) {
                    this.contactsMode = false
                } else {
                    this.contactsMode = true
                }
            },
            sendQuestion() {
                this.form.post('/user-question')
                .then(() => {
                    toast({type: 'success', title: 'Вашето съобщение е изпратено успешно!'})
                    $('#contactsModal').modal('hide');
                    this.closeModal()
                })
                .catch(() => {

                })
            }
        },
        mounted() {
            this.getModal()
        },
    }
</script>
