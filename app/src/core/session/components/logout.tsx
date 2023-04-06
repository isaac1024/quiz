'use client'

import { useSessionContext } from '@/core/session/components/sessionContext'
import { useRouter } from 'next/navigation'

export function Logout() {
  const { logoutHandler } = useSessionContext()
  const router = useRouter()

  const logoutHandle = () => {
    logoutHandler()
    router.push('/')
  }

  return <button onClick={logoutHandle}>Logout</button>
}
