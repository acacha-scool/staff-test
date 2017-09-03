<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choose a teacher:</div>

                    <div class="panel-body">
                        <multiselect v-model="selectedTeacher"
                                     :options="teachers"
                                     :custom-label="label"
                                     placeholder="Select one" label="name"
                                     track-by="name"></multiselect>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


<script>

  import Multiselect from 'vue-multiselect'

  export default {
    components: {
      Multiselect
    },
    data () {
      return {
        selectedTeacher: {},
        teachers: []
      }
    },
    watch: {
      selectedTeacher: function (val) {
        this.$emit('teacherHasBeenSelected', this.selectedTeacherId)
      }
    },
    computed: {
      selectedTeacherId: function () {
        if (this.selectedTeacher) return this.selectedTeacher.id
        else return null
      }
    },
    methods: {
      label ({ id, teacher_code, name, position, type }) {
        return `${id} — [${teacher_code} - ${type} - ${name} - ${position} ]`
      },
      fetchTeachers () {
        this.teachers = [
          { id: 1, teacher_code : '56', name: '', position: 'Tutor CAS', type: 'Matemàtiques'},
          { id: 2, teacher_code : '40', name: 'Sergi Tur Badenas', position: 'Coord info.', type: 'Informàtica'},
          { id: 3, teacher_code : '66', name: '', position: 'Tutor SMX1A', type: 'Informàtica'},
          { id: 4, teacher_code : '86', name: '', position: 'Tutor 1LCB', type: 'Sanitat'},
          { id: 5, teacher_code : '16', name: '', position: '', type: 'Castellà'},
          { id: 6, teacher_code : '06', name: '', position: '', type: 'FOL'}
        ]
      },
      selectDefaultTeacher() {
//        this.selectedTeacher = { id: 1, teacher_code : '56'}
        this.selectedTeacher = null
      },
      choose() {
        if (this.selectedTeacher) this.submit()
        else this.showTeacherNotSelectedError()
      },
      showTeacherNotSelectedError() {
        console.log('No teacher has been selected!')
      },
      submit() {
        //send event to fatther and active user chooser
      }
    },
    mounted() {
        this.fetchTeachers()
        this.selectDefaultTeacher()
    }
  }

</script>
