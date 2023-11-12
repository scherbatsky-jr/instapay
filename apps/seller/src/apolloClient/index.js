import {
  ApolloClient,
  ApolloLink,
  InMemoryCache,
  from,
  fromPromise,
} from "@apollo/client/core";
import { config } from "@/config";
import { createUploadLink } from "apollo-upload-client";
import fetch from "cross-fetch";
import { useAuthStore } from "@/stores/auth";

const link = createUploadLink({ uri: config.apiBaseUrl + "/graphql", fetch });

const authMiddleware = new ApolloLink((operation, forward) => {
  const authStore = useAuthStore();

  const additionalHeaders = {};

  if (authStore.accessToken) {
    operation.setContext({
      headers: {
        authorization: `Bearer ${authStore.accessToken}`,
        ...additionalHeaders,
      },
    });
  }

  if (authStore.refreshTokenPromise) {
    return fromPromise(
      authStore.refreshTokenPromise.then(() => {
        operation.setContext(({ headers = {} }) => ({
          headers: {
            ...headers,
            authorization: `Bearer ${authStore.accessToken}`,
          },
        }));
      })
    ).flatMap(() => forward(operation));
  } else {
    return forward(operation);
  }
});

const client = new ApolloClient({
  cache: new InMemoryCache(),
  link: from([authMiddleware, link]),
});

export default client;
