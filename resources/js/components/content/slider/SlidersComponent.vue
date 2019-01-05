<template>
    <div class="container">
        <a href="#" @click="showModal()"><i class="fas fa-file-import fa-3x green"></i></a>
        <div v-for="slider in sliders" :key="slider.id">
            <router-link class="nav-link" :to="{ name: 'За-спорта', params: { id: slider.id }}">
                <div>
                    <p>{{ slider.title }}</p>
                    <img :src="'/storage/sliders/' + slider.photos" :alt="slider.title">
                </div>
            </router-link>
        </div>

            <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sliderModalLabel">Добавяне на слайд</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createSlider()" id="slidersForm" enctype="multipart/form-data">
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
                            <button type="submit" class="btn btn-success">Добави</button>
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
            getSliders() {
                axios.get("/api/Sliders")
                .then(({ data }) => {this.sliders = data.data});
            },
            showModal(){
                this.form.reset();
                $("#sliderModal").modal("show");
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
            createSlider(){
                if(this.$gate.isAdmin()) {
                    this.form.post('/api/Sliders')
                    .then(() => {
                        $('#sliderModal').modal('hide');
                        this.getSliders()
                    })
                    .catch(() => {

                    })
                }
            }
        },
        mounted() {
            this.getSliders()
        }
    }
</script>



