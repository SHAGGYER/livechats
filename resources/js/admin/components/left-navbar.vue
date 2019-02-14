<template>
    <div>
        <div class="nav-side-menu">
            <div class="brand" @click="openMenu">Admin Control Panel</div>
            <i class="fas fa-align-justify toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
            <div class="menu-list">

                <ul id="menu-content" class="menu-content collapse out">


                    <div v-for="(group, index) in sideMenu.groups" v-bind:key="index">
                        <div v-if="group.links">
                            <li :class="{ active: isGroupActive(index) }"
                                @click="toggleGroupActive(index)"
                                :style="group.style ? group.style : {}"
                                data-toggle="collapse"
                                :data-target="'#group-'+index"
                                style="position:relative;"
                                class="collapsed">
                                <a href="javascript:;" :id="group.id">
                                    {{ group.title }}
                                </a>
                            </li>


                            <ul class="sub-menu collapse" :id="'group-'+index">
                                <li v-for="(link, linkIndex) in group.links"
                                    :key="linkIndex"
                                    @click="redirect(link, 'link', index, linkIndex)"
                                    :class="{ active: isLinkActive(linkIndex, index, link) }">

                                    <router-link v-bind:to="{ name: link.routeName }" v-if="link.type === 'router-link'" :id="link.id">
                                        {{ link.title }}
                                    </router-link>

                                    <a :href="link.href" v-else-if="link.type === 'external'" :id="link.id">
                                        {{ link.title }}
                                    </a>

                                    <a :href="link.href"
                                       @click="link.event"
                                       href="javascript:void(0)"
                                       :id="link.id"
                                       v-else-if="link.type === 'on-click'">
                                        {{ link.title }}
                                    </a>

                                    <router-link v-if="link.type === 'router-link-resource'"
                                                 :to="{ name: 'resource.index', query: { action: link.route } }"
                                                 :id="link.model">
                                        {{ link.plural }}
                                    </router-link>
                                </li>
                            </ul>
                        </div>

                        <div v-else>
                            <li :class="{ active: isGroupActive(index) }" @click="redirect(group, 'group', index)" class="collapsed" :id="group.id">
                                <router-link v-bind:to="{ name: group.routeName }" v-if="group.type === 'router-link'" :id="group.id">
                                    {{ group.title }}
                                </router-link>

                                <a :href="group.href" v-else-if="group.type === 'external'" :id="group.id">
                                    {{ group.title }}
                                </a>
                            </li>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "left-navbar",
        props: ['app'],
        data()
        {
            return {
                activeGroupIndex: 0,
                activeLinkIndex: -1,
                collapsedGroupIndex: -1,

                baseUrl: BASE_URL,


                sideMenu: {
                    groups: [

                        {
                            title: 'Dashboard',
                            type: 'router-link',
                            routeName: 'dashboard',
                        },

                        {
                            title: 'Resources',
                            links: this.app.resources
                        },

                        {
                            title: 'Account',
                            links: [
                                {
                                    type: 'router-link',
                                    title: 'Profile',
                                    routeName: 'profile',

                                },

                                {
                                    type: 'on-click',
                                    title: 'Logout',
                                    event: () => {
                                        this.logout();
                                    }

                                },
                            ]
                        },
                    ]
                }
            }
        },
        watch: {
            '$route.name': function (name) {
                console.log('route changed: from left-navbar');
                this.activateGroupByRoute();
            },

        },

        mounted()
        {
            this.activateGroupByRoute();
        },

        methods: {

            openMenu()
            {
                this.app.$emit('menu-opened');
            },

            activateGroupByRoute()
            {
                for (let g = 0; g < this.sideMenu.groups.length; g++)
                {
                    let currentGroup = this.sideMenu.groups[g];

                    if (currentGroup.links)
                    {
                        for (let l = 0; l < currentGroup.links.length; l++)
                        {
                            let currentLink = currentGroup.links[l];


                            if (currentLink.type === 'router-link-resource')
                            {
                                currentLink.routeName = 'resource.index';
                            }


                            if (this.app.$route.name === currentLink.routeName)
                            {

                                /*
                                Assign new active group index
                                */

                                this.activeGroupIndex = g;

                                /*
                                This group has links
                                */

                                $('#group-'+g).collapse('show');

                                /*
                                If we are already displaying a group's links, hide them
                                 */

                                if (this.collapsedGroupIndex !== g)
                                {
                                    $('#group-'+this.collapsedGroupIndex).collapse('hide');
                                }

                                /*
                                Assign a new collapsed group index.
                                Needed for determining if to hide a previous group
                                   when switching groups
                                 */

                                this.collapsedGroupIndex = g;

                                /*
                                Make the link active
                                */

                                if (currentLink.type === 'router-link-resource')
                                {
                                    if (currentLink.route === this.app.$route.query.action)
                                    {
                                        this.activeLinkIndex = l;

                                    }
                                }
                                else
                                {
                                    this.activeLinkIndex = l;
                                }

                            }
                        }
                    }
                    else
                    {
                        if (this.$route.name === currentGroup.routeName)
                        {
                            this.activeGroupIndex = g;

                            /*
                            If we are already displaying a group's links, hide them
                             */

                            if (this.collapsedGroupIndex !== g)
                            {
                                $('#group-'+this.collapsedGroupIndex).collapse('hide');
                            }
                        }
                    }
                }
            },

            logout()
            {
                this.app.req.post('/admin/logout').then((response) => {
                    this.app.user = null;
                    this.app.$router.push({
                        name: 'login'
                    })
                });
            },

            redirect(link, type, groupIndex, linkIndex)
            {
                if (type === 'group')
                {
                    if ( link.type === 'router-link' )
                    {
                        this.app.$router.push({
                            name: link.routeName
                        });
                    }
                    else if ( link.type === 'router-link-resource' )
                    {
                        this.app.$router.push({
                            name: 'resource.index',
                            query: {
                                action: link.route
                            }
                        })
                    }
                    else if ( link.type === 'external' )
                    {
                        window.location = link.href;
                    }
                    else if ( link.type == 'on-click' )
                    {
                        link.event();
                    }

                    this.toggleGroupActive(groupIndex);

                }
                else if (type === 'link')
                {
                    if ( link.type === 'router-link' )
                    {
                        this.app.$router.push({
                            name: link.routeName
                        });
                    }
                    else if ( link.type === 'external' )
                    {
                        window.location = link.href;
                    }
                    else if ( link.type === 'on-click' )
                    {
                        link.event();
                    }
                    else if ( link.type === 'router-link-resource' )
                    {
                        this.app.$router.push({
                            name: 'resource.index',
                            query: {
                                action: link.route
                            }
                        })
                    }

                    this.makeLinkActive(linkIndex, link);
                }

            },

            toggleGroupActive(index)
            {
                if (this.activeGroupIndex === index)
                {
                    this.activeGroupIndex = -1;
                    $('#group-'+index).collapse('hide');
                }
                else
                {
                    this.activeGroupIndex = index;
                    $('#group-'+index).collapse('show');

                    if (this.collapsedGroupIndex > 0)
                    {
                        $('#group-'+this.collapsedGroupIndex).collapse('hide');
                    }

                    this.collapsedGroupIndex = index;
                }

                this.activeLinkIndex = null;
            },

            isGroupActive(index)
            {
                return index === this.activeGroupIndex;
            },

            isLinkActive(linkIndex, groupIndex, link)
            {
                if (linkIndex === this.activeLinkIndex)
                    return true;

                return false;
            },

            makeLinkActive(index, link)
            {
                this.activeLinkIndex = index;
            }
        },

    }
</script>

<style scoped>
    .arrow
    {
        position: absolute;
        bottom: 5px;
        right: 5px;
    }

    .nav-side-menu {
        overflow: auto;
        font-family: verdana;
        font-size: 15px;
        font-weight: 200;
        background-color: #5e5f60;
        top: 0px;
        width: 100%;
        height: 100vh;
        color: #e1ffff;
    }
    .nav-side-menu .brand {
        background-color: #23282e;
        height: 85px;
        display: block;
        text-align: left;
        font-size: 19px;
        padding-top: 14px;
        padding-left: 14px;
    }
    .nav-side-menu .toggle-btn {
        display: none;
    }
    .nav-side-menu ul,
    .nav-side-menu li {
        list-style: none;
        padding: 0px;
        margin: 0px;
        line-height: 40px;
        cursor: pointer;
    }

    .nav-side-menu ul .active,
    .nav-side-menu li .active {
        border-left: 3px solid #d19b3d;
        background-color: #4f5b69;
    }
    .nav-side-menu ul .sub-menu li.active,
    .nav-side-menu li .sub-menu li.active {
        color: #d19b3d;
    }
    .nav-side-menu ul .sub-menu li.active a,
    .nav-side-menu li .sub-menu li.active a {
        color: #d19b3d;
    }
    .nav-side-menu ul .sub-menu li,
    .nav-side-menu li .sub-menu li {
        background-color: #181c20;
        border: none;
        line-height: 41px;
        border-bottom: 1px solid #23282e;
        margin-left: 0px;
        padding-left: 12px;
    }
    .nav-side-menu ul .sub-menu li:hover,
    .nav-side-menu li .sub-menu li:hover {
        background-color: #020203;
    }
    .nav-side-menu li {
        padding-left: 0.4rem;
        border-left: 3px solid #2e353d;
        border-bottom: 1px solid #23282e;
    }
    .nav-side-menu li a {
        text-decoration: none;
        color: #e1ffff;
    }
    .nav-side-menu li a i {
        padding-left: 10px;
        width: 20px;
        padding-right: 20px;
    }
    .nav-side-menu li:hover {
        border-left: 3px solid #d19b3d;
        background-color: #4f5b69;
        -webkit-transition: all 1s ease;
        -moz-transition: all 1s ease;
        -o-transition: all 1s ease;
        -ms-transition: all 1s ease;
        transition: all 1s ease;
    }
    @media (max-width: 992px) {
        .nav-side-menu {
            position: relative;
            width: 100%;
            margin-bottom: 10px;
        }
        .nav-side-menu .toggle-btn {
            display: block;
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 10 !important;
            padding: 3px;
            background-color: #ffffff;
            color: #000;
            width: 40px;
            text-align: center;
        }
        .brand {
            text-align: left !important;
            font-size: 22px;
            padding-left: 20px;
            line-height: 50px !important;
        }

        .nav-side-menu .brand {
            padding-top: 0px;
            height: auto;
        }
    }
    @media (min-width: 992px) {
        .nav-side-menu .menu-list .menu-content {
            display: block;
        }
    }


    body {
        margin: 0px;
        padding: 0px;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>