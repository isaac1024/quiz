'use client'

import { useLogin } from '@/core/session/hooks/useLogin'
import { useSessionContext } from '@/core/session/components/sessionContext'
import { useRouter } from 'next/navigation'
import { Button } from '@/core/shared/elements/Button'
import { TextInput } from '@/core/shared/elements/TextInput'
import styles from './page.module.css'

export default function Home() {
  const { data, dataError, onChangePassword, onChangeUsername, onSubmitForm } =
    useLogin()
  const { session: sessionContext } = useSessionContext()
  const router = useRouter()

  if (sessionContext.token) {
    router.push('/')
  }

  return (
    <form className={styles['login-form']} onSubmit={onSubmitForm}>
      <label htmlFor="username">Email</label>
      <TextInput
        name="username"
        value={data.username}
        onChange={onChangeUsername}
      />
      <small>{dataError.username}</small>
      <label htmlFor="password">Password</label>
      <TextInput
        name="password"
        type="password"
        value={data.password}
        onChange={onChangePassword}
      />
      <small>{dataError.password}</small>
      <Button>Login</Button>
    </form>
  )
}
