export type Session = {
  token: string
}

export type SessionStore = {
  session: Session
  loginHandler: (token: string) => void
  logoutHandler: () => void
}

export type Login = {
  username: string
  password: string
}

export const emailRegexp = /^[\w_\-.]+@([\w_-]+\.)+[\w-]{2,}$/
