import {ApolloClient} from 'apollo-client';
import {InMemoryCache} from 'apollo-cache-inmemory';

const { createUploadLink } = require('apollo-upload-client')

export const restLink = process.env.REACT_APP_API_HOST + ":" + process.env.REACT_APP_API_PORT;
export const client = new ApolloClient({
    link: createUploadLink({uri: restLink + "/graphql/"}),
    cache: new InMemoryCache({ addTypename: false })
});