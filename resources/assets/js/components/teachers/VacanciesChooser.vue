<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choose a vacancy:</div>

                    <ul class="filters">
                        <li><a href="#/all" :class="{ selected: visibility == 'all' }">All</a></li>
                        <li><a href="#/assigned" :class="{ selected: visibility == 'assigned' }">Assigned</a></li>
                        <li><a href="#/pending" :class="{ selected: visibility == 'pending' }">Pending</a></li>
                    </ul>

                    <div class="panel-body">
                        <multiselect v-model="selectedTeacher"
                                     :options="filteredTeachers"
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
        vacancies: [],
        visibility: 'all',
        filters: {
          all: function (vacancies) {
            return vacancies
          },
          assigned: function (vacancies) {
            return vacancies.filter(function (vacancy) {
              return vacancy.state === 'assigned'
            })
          },
          pending: function (vacancies) {
            return vacancies.filter(function (vacancy) {
              return vacancy.state === 'pending'
            })
          }
        }
      }
    },
    watch: {
      selectedTeacher: function (val) {
        this.$emit('vacancyHasBeenSelected', this.selectedTeacherId)
      }
    },
    computed: {
      selectedTeacherId: function () {
        if (this.selectedTeacher) return this.selectedTeacher.id
        else return null
      },
      filteredTeachers: function () {
        return this.filters[this.visibility](this.vacancies)
      },
    },
    methods: {
      label ({ id, code, state, positions, speciality, user }) {
//        console.log('Id: ' + id)
//        console.log('code: ' + code)
//        console.log('state: ' + state)
//        console.log('positions: ' + positions)
//        console.log('speciality: ' + speciality)
//        console.log('user: ' + user)
        if ( positions !== undefined) {
          var positionNames = positions.map(function(x){
            return x.name
          })
        } else {
          positionNames = ''
        }
        if ( speciality !== undefined) {
          speciality = speciality.code + ' | ' + speciality.name
        } else {
          speciality = ''
        }
        if ( user !== undefined) {
          if ( user.name !== undefined) {
            user = user.name
          } else {
            user = 'Not assigned'
          }
        } else {
          user = 'Not assigned'
        }
        console.log('user: ' + user)
        return `${id} — [${code} - User: ${user} - State: ${state} - Speciality: ${speciality} - Positions: ${positionNames} ]`
      },
      fetchTeachers () {
        var component = this
        axios.get('/api/v1/vacancies?paginate=false')
          .then(response => {
            component.vacancies = response.data.data
          })
          .catch(error => {
            console.log(error)
          })
      },
      selectDefaultTeacher() {
        this.selectedTeacher = null
      },
      choose() {
        if (this.selectedTeacher) this.submit()
        else this.showTeacherNotSelectedError()
      },
      showTeacherNotSelectedError() {
        console.log('No vacancy has been selected!')
      },
      onHashChange () {
        console.log('onHashChange!!!!')
        var visibility = window.location.hash.replace(/#\/?/, '')
        if (this.filters[visibility]) {
          this.visibility = visibility
        } else {
          window.location.hash = ''
          this.visibility = 'all'
        }
      }
    },
    mounted() {
      this.fetchTeachers()
      this.selectDefaultTeacher()
      window.addEventListener('hashchange', this.onHashChange)
      this.onHashChange()
    }
  }

</script>
