import { ChangeEvent, useState } from 'react'
import { useSessionContext } from '@/core/session/components/sessionContext'
import { useRouter } from 'next/navigation'
import { Login, emailRegexp } from '@/core/session/session'
import { login } from '@/core/session/services'

export function useLogin() {
  const [data, setData] = useState<Login>({ username: '', password: '' })
  const [dataError, setDataError] = useState<Login>({
    username: '',
    password: '',
  })
  const { loginHandler } = useSessionContext()
  const router = useRouter()

  const onChangePassword = (event: ChangeEvent<HTMLInputElement>): void => {
    const password = event.target.value
    setData({ ...data, password })
    setDataError({ ...dataError, password: '' })
  }

  const validateEmail = (email: string): string => {
    if (!email) {
      return 'Email is required'
    }

    if (!emailRegexp.test(email)) {
      return 'Invalid email format'
    }

    return ''
  }

  const onChangeUsername = (event: ChangeEvent<HTMLInputElement>): void => {
    const email = event.target.value
    setData({ ...data, username: email })
    setDataError({ ...dataError, username: validateEmail(email) })
  }

  const onSubmitForm = async (
    event: ChangeEvent<HTMLFormElement>
  ): Promise<void> => {
    event.preventDefault()

    const response = await login(data)
    const session = await response.json()

    if (!response.ok) {
      setDataError({ ...dataError, password: session.message })
      return
    }

    loginHandler(session.token)
    router.push('/')
  }

  return { data, dataError, onChangePassword, onChangeUsername, onSubmitForm }
}
