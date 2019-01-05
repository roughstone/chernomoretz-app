<template>
    <div>
        <div v-for="slider in sliders" :key="slider.id">
            <h1>{{ slider.title }}</h1>
            <h1>{{ slider.id }}</h1>
            <img :src="'/storage/sliders/' + slider.photos" :alt="slider.title"/>
            <p>{{ slider.id }}</p>
            <a href="#" @click="editSlider(slider)"><i class="fas fa-edit yellow"></i>&#160;</a>
            <a href="" @click="deleteSlider(slider.id)"><i class="fas fa-trash red"></i>&#160;</a>
        </div>
        <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sliderModalLabel">Промяна на слахд</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateSlider()" id="sliderForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Заглавие" required>
                                <has-error :form="form" field="title"></has-error>
                            </div>
                            <div class="form-group">
                             <input type="file" @change="onFileSelect" name="photos" class="form-control">
                            </div>
                            <div class="form-group">
                                <input v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Описание" required>
                                <has-error :form="form" field="description"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Промени</button>
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
                sliders: null,
                form: new Form({
                    title : '',
                    photos : '',
                    description : '',
                })
            }
        },
        methods :{
            getSlider() {
                axios.get("/api/Sliders/" + this.$route.params.id)
                .then(res => {this.sliders = res.data})
                .catch(() => {

                });
            },
            updateSlider(){

            },
            editSlider (slider) {
                $("#sliderModal").modal("show");
                this.form.fill( slider );
            },
            onFileSelect(event) {
                let file = event.target.files[0];
                // console.log(file)
                this.form.photos = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                   this.form.photos = reader.result
                }
                reader.readAsDataURL(file)
            },
            updateSlider() {
                this.form.put('/api/Sliders/' + this.$route.params.id)
                .then(() => {
                    $('#sliderModal').modal('hide')
                    this.getSlider();
                })
                .catch(() => {
                })
            },

            deleteSlider (id) {
                axios.delete('/api/Sliders/' + id)
                .then(() => {
                    location.reload()
                })
                .catch(() => {

                })
            },

        },
        mounted() {
            this.getSlider()
        }
    }
</script>
