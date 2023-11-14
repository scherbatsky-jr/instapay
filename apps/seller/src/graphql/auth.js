import gql from "graphql-tag";

export const loginMutation = gql`
  mutation loginMutation(
    $password: String!
    $username: String!
    $withRoles: [String!]!
  ) {
    login(password: $password, username: $username, withRoles: $withRoles) {
      authTokens: auth_tokens {
        accessToken: access_token
        expiresIn: expires_in
        refreshToken: refresh_token
      }
      user {
        email
        id
        profiles {
          given_name
          surname
        }
        username
      }
    }
  }
`;

export const logoutMutation = gql`
  mutation logoutMutation {
    result: logout
  }
`;

export const refreshTokenMutation = gql`
  mutation refreshToken($refreshToken: String!) {
    refreshToken(refresh_token: $refreshToken) {
      accessToken: access_token
      expiresIn: expires_in
      refreshToken: refresh_token
    }
  }
`;

export const resetPasswordMutation = gql`
  mutation resetPassword($password: String!, $token: String!) {
    resetPassword(password: $password, token: $token) {
      result
    }
  }
`;

export const resetPasswordRequestMutation = gql`
  mutation resetPasswordRequest($url: String!, $email: String!) {
    resetPasswordRequest(url: $url, email: $email) {
      result
    }
  }
`;

export const signUpMutation = gql`
  mutation signup(
    $password: String!
    $username: String!
    $withRoles: [String!]!
    $profile: ProfileInput!
    ) {
    signup(
      password: $password
      username: $username
      withRoles: $withRoles
      profile: $profile
    ) {
      authTokens: auth_tokens {
        accessToken: access_token
        expiresIn: expires_in
        refreshToken: refresh_token
      }
      user {
        id
        email
        username
        profiles {
          given_name
          surname
          contact
          status
        }
      }
    }
  }
`;
