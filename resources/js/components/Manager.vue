<template>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8mb-2">
                <form class="form-inline" @submit.prevent="createTeam">
                    <div class="form-group">
                        <div class="d-flex flex-column">
                            <input type="text"
                                   v-model="newTeamForm.name"
                                   class="form-control flex" name="team_name" id="team_name"
                                   placeholder="Team name">
                            <small class="form-text text-danger" v-if="newTeamForm.error"
                                   v-text="newTeamForm.error"></small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-link" @click="">Create team</button>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mb-4" v-for="team in teams">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div v-text="team.name"></div>
                        <button class="btn btn-link" @click="showAddPlayerDialog(team)">Add player</button>
                    </div>

                    <div class="card-body">
                        <ul>
                            <li v-for="player in team.players" :key="player.id">
                                <div class="row">
                                    <div class="col flex-1" v-text="player.name"></div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-link"
                                                @click="showEditPlayerDialog(player,team)">
                                            Edit
                                        </button>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-link" @click="deletePlayer(player,team)">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <b-modal ref="playerModalForm" hide-footer :title="playerModal.title">
            <form @submit.prevent="handlePlayerSubmit">
                <div class="form-group">
                    <input type="text" class="form-control"
                           name="first_name"
                           id="first_name"
                           v-model="playerModal.form.first_name"
                           placeholder="First name">
                </div>
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           name="last_name"
                           id="last_name"
                           v-model="playerModal.form.last_name"
                           placeholder="Last name">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import BModal from 'bootstrap-vue/es/components/modal/modal'

    export default {
        name: "Manager",
        components: {
            'b-modal': BModal
        },
        data() {
            return {
                newTeamForm: {
                    name: "",
                    error: null
                },
                playerModal: {
                    show: false,
                    edit: false,
                    title: "",
                    form: {
                        first_name: "",
                        last_name: "",
                        team: null,
                        player: null,
                    },
                },
                teams: [],
                user: null
            };
        },
        async created() {
            await axios.get('/user').then(({data}) => {
                this.user = data;
            });
            if(this.user.api_token){
                axios.interceptors.request.use((config) => {
                    config.headers.authorization = `Bearer ${this.user.api_token}`;
                    return config;
                });
            }

            await axios.get('/api/teams').then(response => {
                this.teams.push(...response.data)
            })
        },
        methods: {
            showAddPlayerDialog(team) {
                this.playerModal.edit = false;
                this.playerModal.form.team = team;
                this.playerModal.title = `Add new player to team: <strong>${team.name}</strong>`;
                this.playerModal.form.player = false;
                this.playerModal.form.first_name = "";
                this.playerModal.form.last_name = "";

                this.$refs.playerModalForm.show();
            },
            createTeam() {
                if (this.newTeamForm.name) {
                    this.newTeamForm.error = null;
                    axios.post('/api/teams', {
                        name: this.newTeamForm.name
                    })
                        .then(response => {
                            let team = Object.assign(response.data, {players: []});
                            this.teams.unshift(team)
                        })
                        .catch(err => {
                            if (err.response && err.response.status === 422) {
                                this.newTeamForm.error = err.response.data.errors.name[0]
                            }
                        })

                }
            },
            addPlayer() {
                let team = this.playerModal.form.team;
                axios.post(`/api/teams/${team.id}/players`, {
                    first_name: this.playerModal.form.first_name,
                    last_name: this.playerModal.form.last_name,
                    team_id: team.id
                })
                    .then(response => {
                        team.players.push(response.data);
                        this.$refs.playerModalForm.hide();
                    })
            },
            showEditPlayerDialog(player, team) {
                this.playerModal.edit = true;
                this.playerModal.form.team = team;
                this.playerModal.title = `Edit player`;
                this.playerModal.form.player = player;
                this.playerModal.form.first_name = player.first_name;
                this.playerModal.form.last_name = player.last_name;

                this.$refs.playerModalForm.show();
            },
            updatePlayer() {
                let form = this.playerModal.form;
                let player = form.player;
                axios.put(`/api/players/${form.player.id}`, {
                    first_name: form.first_name,
                    last_name: form.last_name,
                    team_id: form.team.id,
                }).then(({data}) => {
                    player = Object.assign(player, data);
                    this.$refs.playerModalForm.hide();
                })
            },
            deletePlayer(player, team) {
                axios.delete(`/api/players/${player.id}`)
                    .then(response => {
                        team.players.splice(team.players.indexOf(player), 1)
                        this.$refs.playerModalForm.hide();
                    });
            },
            handlePlayerSubmit() {
                return this.playerModal.edit ? this.updatePlayer() : this.addPlayer();
            }
        }
    }
</script>
