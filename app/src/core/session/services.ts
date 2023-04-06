import { Login } from '@/core/session/session'

const baseUrl = process.env.API_HOST

export async function login({ username, password }: Login) {
  return fetch(`${baseUrl}/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password }),
  })
}
