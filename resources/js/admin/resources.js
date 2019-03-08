export default [
    {
        title: 'User',
        plural: 'Users',
        model: 'App\\User',
        route: 'user',
        type: 'router-link-resource',
        fields: [
            {
                name: 'ID',
                column: 'id',
                auto: true,
            },

            {
                name: 'Name',
                column: 'name',
                type: 'text',
                auto: false
            },

            {
                name: 'Username',
                column: 'username',
                type: 'text',
                auto: false,
            },

            {
                name: 'Password',
                column: 'password',
                type: 'password',
                bcrypt: true,
                auto: false,
            },


            {
                name: 'Created At',
                column: 'created_at',
                auto: true,
            },

            {
                name: 'Updated At',
                column: 'updated_at',
                auto: true,
            }
        ]
    },

    {
        title: 'Room',
        plural: 'Rooms',
        model: 'App\\Room',
        route: 'room',
        type: 'router-link-resource',
        fields: [
            {
                name: 'ID',
                column: 'id',
                auto: true,
            },

            {
                name: 'Title',
                column: 'title',
                type: 'text',
                auto: false
            },

            {
                name: 'Created At',
                column: 'created_at',
                auto: true,
            },

            {
                name: 'Updated At',
                column: 'updated_at',
                auto: true,
            }
        ]
    },

    {
        title: 'Feedback',
        plural: 'Feedback',
        model: 'App\\Feedback',
        route: 'feedback',
        type: 'router-link-resource',
        fields: [
            {
                name: 'ID',
                column: 'id',
                auto: true,
            },

            {
                name: 'Body',
                column: 'body',
                type: 'text',
                auto: false
            },

            {
                name: 'Created At',
                column: 'created_at',
                auto: true,
            },

            {
                name: 'Updated At',
                column: 'updated_at',
                auto: true,
            }
        ]
    },

];