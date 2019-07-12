<template>
<div class="wrap">
    <h1>Google Maps Geocoding</h1>

    <div id="map" class="map-box wrap" v-if="lng && lat">
        <gmap-map 
            :center="marker"
            :zoom="18"
            style="width: 100%; height: 500px">
        </gmap-map>
    </div>

    <div class="form-box wrap">
        <div class="error" v-if="error"><p>{{error}}</p></div>
        <div class="form-item">
            <p>Введите название обьекта или его координаты (например ООО "МАЙ КУЛ")</p>
            <input id="name" v-model="geodata">
        </div><br>
        <div class="form-item">
            <p>Введите язык ввода информации</p>
            <select id="leng" v-model="leng">
                <option value="Ru">ru</option>
                <option value="En">en</option>
            </select>
        </div><br>
        <button id="submit_btn"  @click.prevent="sendData" class="btn" type="submit">Поиск</button>
    </div>
</div>
</template>

<script>
import {gmapApi} from 'vue2-google-maps'
export default {
    data(){
        return {
            geodata: '',
            leng: '',
            data: null,
            lng: null,
            lat: null,
            marker: {
                lat: 50.60229509638775,
                lng: 3.0247059387528408
            },
            error: null
        }
    },

    computed: {
        google: gmapApi
    },
    

    methods:{
        sendData(){

            this.error = false;

            if(this.geodata === "" || this.leng === ""){
                this.error = "Вы не ввели данные"
            }else{
                let currentObj = this;
                axios.post('/geo', {
                    geodata: currentObj.geodata,
                    leng: currentObj.leng
                },
                {
                    headers: {
                        'accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(function (response) {
                    if(response){
                        if(response.data.data.original){
                            currentObj.error = response.data.data.original.error;
                        }
                        
                        if(response.data.data.error){
                            currentObj.error = response.data.data.original.data;
                        }else{
                            currentObj.data = response;
                            currentObj.lat = response.data.data.geometry.location.lat;
                            currentObj.lng = response.data.data.geometry.location.lng;
    
                            currentObj.marker.lat = response.data.data.geometry.location.lat;
                            currentObj.marker.lng = response.data.data.geometry.location.lng;
                        }
                                               
                    }
                })
                .catch(function (error) {
                    if(error){
                        console.log(error.response);
                    }
                })
            }
        }
    }
}

</script>

<style scoped>
.wrap{
    padding: 10px 90px 10px 90px;
}

.form-box{
    border: 1px solid #f0f0f0;
}
.error{
    color: red;
    font-weight: bold;
}
</style>