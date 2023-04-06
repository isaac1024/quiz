'use client'

import React, { createContext, useContext, useState } from 'react'
import { Session, SessionStore } from '@/core/session/session'

const SessionContext = createContext<SessionStore>({
  session: { token: '' },
  loginHandler: (token: string) => {},
  logoutHandler: () => {},
})

export function SessionContextProvider({
  children,
}: {
  children: React.ReactNode
}) {
  const [session, setSession] = useState<Session>({ token: '' })
  const loginHandler = (token: string) => setSession({ token })

  const logoutHandler = () => setSession({ token: '' })

  return (
    <SessionContext.Provider value={{ session, loginHandler, logoutHandler }}>
      {children}
    </SessionContext.Provider>
  )
}

export const useSessionContext = () => useContext(SessionContext)
